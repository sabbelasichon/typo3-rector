<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v9\v5;

use Nette\Utils\Strings;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\Parser as NikicParser;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTextNode;
use PHPStan\Type\ObjectType;
use Rector\Core\Application\FileSystem\RemovedAndAddedFilesCollector;
use Rector\Core\Configuration\Configuration;
use Rector\Core\PhpParser\Parser\Parser;
use Rector\Core\PhpParser\Printer\BetterStandardPrinter;
use Rector\Core\Rector\AbstractRector;
use Rector\FileSystemRector\ValueObject\AddedFileWithContent;
use Ssch\TYPO3Rector\Helper\FilesFinder;
use Ssch\TYPO3Rector\Rector\v9\v5\ExtbaseCommandControllerToSymfonyCommand\AddArgumentToSymfonyCommandRector;
use Ssch\TYPO3Rector\Rector\v9\v5\ExtbaseCommandControllerToSymfonyCommand\AddCommandsToReturnRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Symplify\SmartFileSystem\SmartFileInfo;
use Symplify\SmartFileSystem\SmartFileSystem;

/**
 * @changelog https://docs.typo3.org/m/typo3/reference-coreapi/9.5/en-us/ApiOverview/CommandControllers/Index.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v9\v5\ExtbaseCommandControllerToSymfonyCommandRector\ExtbaseCommandControllerToSymfonyCommandRectorTest
 */
final class ExtbaseCommandControllerToSymfonyCommandRector extends AbstractRector
{
    public function __construct(
        private SmartFileSystem $smartFileSystem,
        private Parser $parser,
        BetterStandardPrinter $betterStandardPrinter,
        private AddArgumentToSymfonyCommandRector $addArgumentToSymfonyCommandRector,
        private FilesFinder $filesFinder,
        private AddCommandsToReturnRector $addCommandsToReturnRector,
        RemovedAndAddedFilesCollector $removedAndAddedFilesCollector,
        private NikicParser $nikicParser,
        private Configuration $configuration
    ) {
        $this->betterStandardPrinter = $betterStandardPrinter;
        $this->removedAndAddedFilesCollector = $removedAndAddedFilesCollector;
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    /**
     * @param Class_ $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isObjectType($node, new ObjectType('TYPO3\CMS\Extbase\Mvc\Controller\CommandController'))) {
            return null;
        }

        $commandMethods = $this->findCommandMethods($node);

        if ([] === $commandMethods) {
            return null;
        }

        if ([] === $node->namespacedName->parts) {
            return null;
        }

        // This is super hacky, but for now i have no other idea to test it here
        $currentSmartFileInfo = $this->file->getSmartFileInfo();

        $extEmConfFileInfo = $this->filesFinder->findExtEmConfRelativeFromGivenFileInfo($currentSmartFileInfo);

        if (null === $extEmConfFileInfo) {
            return null;
        }

        $extensionDirectory = dirname($extEmConfFileInfo->getRealPath());

        $commandsFilePath = sprintf('%s/Configuration/Commands.php', $extensionDirectory);

        $namespaceParts = $node->namespacedName->parts;

        $vendorName = array_shift($namespaceParts);
        $extensionName = array_shift($namespaceParts);
        $commandNamespace = sprintf('%s\%s\Command', $vendorName, $extensionName);

        // Collect all new commands
        $newCommandsWithFullQualifiedNamespace = [];

        foreach ($commandMethods as $commandMethod) {
            if (! $commandMethod instanceof ClassMethod) {
                continue;
            }

            $commandMethodName = $this->getName($commandMethod->name);

            if (null === $commandMethodName) {
                continue;
            }

            if (null === $commandMethod->stmts) {
                continue;
            }

            $commandPhpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($commandMethod);

            $paramTags = $commandPhpDocInfo->getParamTagValueNodes();

            $descriptionPhpDocNode = $commandPhpDocInfo->getByType(PhpDocTextNode::class);

            $methodParameters = $commandMethod->params;
            $commandDescription = null !== $descriptionPhpDocNode ? (string) $descriptionPhpDocNode : '';

            $commandTemplate = new SmartFileInfo(__DIR__ . '/../../../../templates/maker/Commands/Command.tpl.php');
            $commandName = Strings::firstUpper($commandMethodName);
            $commandContent = $commandTemplate->getContents();

            $filePath = sprintf('%s/Classes/Command/%s.php', $extensionDirectory, $commandName);

            // Do not overwrite existing file
            if ($this->smartFileSystem->exists($filePath)) {
                continue;
            }

            $commandVariables = [
                '__TEMPLATE_NAMESPACE__' => ltrim($commandNamespace, '\\'),
                '__TEMPLATE_COMMAND_NAME__' => $commandName,
                '__TEMPLATE_DESCRIPTION__' => $commandDescription,
                '__TEMPLATE_COMMAND_BODY__' => $this->betterStandardPrinter->prettyPrint($commandMethod->stmts),
            ];

            // Add traits, other methods etc. to class
            // Maybe inject dependencies into __constructor

            $commandContent = str_replace(array_keys($commandVariables), $commandVariables, $commandContent);

            $nodes = $this->nikicParser->parse($commandContent);

            if (null === $nodes) {
                $nodes = [];
            }

            $this->decorateNamesToFullyQualified($nodes);

            $nodeTraverser = new NodeTraverser();

            $inputArguments = [];
            foreach ($methodParameters as $key => $methodParameter) {
                $paramTag = $paramTags[$key] ?? null;

                $methodParamName = $this->nodeNameResolver->getName($methodParameter->var);

                if (null === $methodParamName) {
                    continue;
                }

                $inputArguments[$methodParamName] = [
                    'name' => $methodParamName,
                    'description' => null !== $paramTag ? $paramTag->description : '',
                    'mode' => null !== $methodParameter->default ? 2 : 1,
                    'default' => $methodParameter->default,
                ];
            }

            $this->addArgumentToSymfonyCommandRector->configure([
                AddArgumentToSymfonyCommandRector::INPUT_ARGUMENTS => $inputArguments,
            ]);
            $nodeTraverser->addVisitor($this->addArgumentToSymfonyCommandRector);
            $nodes = $nodeTraverser->traverse($nodes);

            $changedSetConfigContent = $this->betterStandardPrinter->prettyPrintFile($nodes);

            $this->createDeepDirectoryFromFilePath($filePath);

            $this->removedAndAddedFilesCollector->addAddedFile(
                new AddedFileWithContent($filePath, $changedSetConfigContent)
            );

            $newCommandName = sprintf('%s:%s', Strings::lower($vendorName), Strings::lower($commandName));
            $newCommandsWithFullQualifiedNamespace[$newCommandName] = sprintf('%s\%s', $commandNamespace, $commandName);
        }

        $this->addNewCommandsToCommandsFile($commandsFilePath, $newCommandsWithFullQualifiedNamespace);

        $this->addArgumentToSymfonyCommandRector->configure([
            AddArgumentToSymfonyCommandRector::INPUT_ARGUMENTS => [],
        ]);
        $this->addCommandsToReturnRector->configure([
            AddCommandsToReturnRector::COMMANDS => [],
        ]);

        return $node;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate from extbase CommandController to Symfony Command', [
            new CodeSample(
                <<<'CODE_SAMPLE'
use TYPO3\CMS\Extbase\Mvc\Controller\CommandController;

final class TestCommand extends CommandController
{
    /**
     * This is the description of the command
     *
     * @param string Foo The foo parameter
     */
    public function fooCommand(string $foo)
    {

    }
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class FooCommand extends Command
{
    protected function configure(): void
    {
        $this->setDescription('This is the description of the command');
        $this->addArgument('foo', \Symfony\Component\Console\Input\InputArgument::REQUIRED, 'The foo parameter', null);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        return 0;
    }
}
CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @return Node[]|ClassMethod[]
     */
    private function findCommandMethods(Class_ $node): array
    {
        return $this->betterNodeFinder->find($node->stmts, function (Node $node) {
            if (! $node instanceof ClassMethod) {
                return false;
            }

            if (! $node->isPublic()) {
                return false;
            }

            $methodName = $this->getName($node->name);

            if (null === $methodName) {
                return null;
            }

            return Strings::endsWith($methodName, 'Command');
        });
    }

    /**
     * @param array<string, string> $newCommandsWithFullQualifiedNamespace
     */
    private function addNewCommandsToCommandsFile(
        string $commandsFilePath,
        array $newCommandsWithFullQualifiedNamespace
    ): void {
        if ($this->smartFileSystem->exists($commandsFilePath)) {
            $commandsSmartFileInfo = new SmartFileInfo($commandsFilePath);
            $nodes = $this->parser->parseFileInfo($commandsSmartFileInfo);
        } else {
            $this->createDeepDirectoryFromFilePath($commandsFilePath);
            $defaultsCommandsTemplate = new SmartFileInfo(
                __DIR__ . '/../../../../templates/maker/Commands/Commands.tpl.php'
            );
            $nodes = $this->parser->parseFileInfo($defaultsCommandsTemplate);
        }

        $this->decorateNamesToFullyQualified($nodes);

        $nodeTraverser = new NodeTraverser();
        $this->addCommandsToReturnRector->configure([
            AddCommandsToReturnRector::COMMANDS => $newCommandsWithFullQualifiedNamespace,
        ]);
        $nodeTraverser->addVisitor($this->addCommandsToReturnRector);
        $nodes = $nodeTraverser->traverse($nodes);

        $changedCommandsContent = $this->betterStandardPrinter->prettyPrintFile($nodes);
        $this->removedAndAddedFilesCollector->addAddedFile(
            new AddedFileWithContent($commandsFilePath, $changedCommandsContent)
        );
    }

    /**
     * @param Node[] $nodes
     */
    private function decorateNamesToFullyQualified(array $nodes): void
    {
        // decorate nodes with names first
        $nameResolverNodeTraverser = new NodeTraverser();
        $nameResolverNodeTraverser->addVisitor(new NameResolver());
        $nameResolverNodeTraverser->traverse($nodes);
    }

    private function createDeepDirectoryFromFilePath(string $filePath): void
    {
        if ($this->configuration->isDryRun()) {
            return;
        }

        $this->smartFileSystem->mkdir(dirname($filePath));
    }
}

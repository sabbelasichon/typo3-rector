<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v12\v0\typo3;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Return_;
use PhpParser\NodeTraverser;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\ObjectType;
use Rector\Core\Application\FileSystem\RemovedAndAddedFilesCollector;
use Rector\Core\PhpParser\Parser\SimplePhpParser;
use Rector\Core\PhpParser\Printer\NodesWithFileDestinationPrinter;
use Rector\Core\Rector\AbstractRector;
use Rector\FileSystemRector\ValueObject\AddedFileWithContent;
use Rector\FileSystemRector\ValueObject\AddedFileWithNodes;
use Ssch\TYPO3Rector\Helper\FilesFinder;
use Ssch\TYPO3Rector\Helper\SymfonyYamlParser;
use Ssch\TYPO3Rector\NodeVisitor\RemoveExtbaseTypeConverterNodeVisitor;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Symplify\SmartFileSystem\SmartFileInfo;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/12.0/Breaking-94117-RegisterExtbaseTypeConvertersAsServices.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\RegisterExtbaseTypeConvertersAsServicesRector\RegisterExtbaseTypeConvertersAsServicesRectorTest
 */
final class RegisterExtbaseTypeConvertersAsServicesRector extends AbstractRector
{
    /**
     * @readonly
     */
    private ReflectionProvider $reflectionProvider;

    /**
     * @readonly
     */
    private SimplePhpParser $simplePhpParser;

    /**
     * @readonly
     */
    private FilesFinder $filesFinder;

    private RemovedAndAddedFilesCollector $removedAndAddedFilesCollector;

    private NodesWithFileDestinationPrinter $nodesWithFileDestinationPrinter;

    private SymfonyYamlParser $symfonyYamlParser;

    public function __construct(
        ReflectionProvider $reflectionProvider,
        SimplePhpParser $simplePhpParser,
        FilesFinder $filesFinder,
        RemovedAndAddedFilesCollector $removedAndAddedFilesCollector,
        NodesWithFileDestinationPrinter $nodesWithFileDestinationPrinter,
        SymfonyYamlParser $symfonyYamlParser
    ) {
        $this->reflectionProvider = $reflectionProvider;
        $this->simplePhpParser = $simplePhpParser;
        $this->filesFinder = $filesFinder;
        $this->removedAndAddedFilesCollector = $removedAndAddedFilesCollector;
        $this->nodesWithFileDestinationPrinter = $nodesWithFileDestinationPrinter;
        $this->symfonyYamlParser = $symfonyYamlParser;
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [StaticCall::class];
    }

    /**
     * @param StaticCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($this->shouldSkip($node)) {
            return null;
        }

        $className = $this->valueResolver->getValue($node->args[0]->value);

        if (! $this->reflectionProvider->hasClass($className)) {
            return null;
        }

        $classReflection = $this->reflectionProvider->getClass($className);

        $fileName = $classReflection->getFileName();

        if (! is_string($fileName) || ! file_exists($fileName)) {
            return null;
        }

        $extEmConf = $this->filesFinder->findExtEmConfRelativeFromGivenFileInfo(
            new SmartFileInfo($this->file->getFilePath())
        );

        if (! $extEmConf instanceof SmartFileInfo) {
            return null;
        }

        $classStatements = $this->simplePhpParser->parseFile($fileName);
        $originalClassStatements = $classStatements;

        $collectServiceTags = $this->collectServiceTags($classStatements);

        $existingServicesYamlFilePath = $extEmConf->getRealPathDirectory() . '/Configuration/Services.yaml';

        $yamlConfiguration = $this->getYamlConfiguration($existingServicesYamlFilePath);

        if (! isset($yamlConfiguration['services'])) {
            $yamlConfiguration['services'] = [];
        }

        if (! isset($yamlConfiguration['services'][$className])) {
            $yamlConfiguration['services'][$className]['tags'] = $collectServiceTags;
        }

        $yamlConfigurationAsYaml = $this->symfonyYamlParser->dump($yamlConfiguration);

        $servicesYaml = new AddedFileWithContent($existingServicesYamlFilePath, $yamlConfigurationAsYaml);
        $this->removedAndAddedFilesCollector->addAddedFile($servicesYaml);

        $this->nodeRemover->removeNode($node);
        $this->removeClassMethodsFromTypeConverter($fileName, $originalClassStatements);

        return null;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Register extbase type converters as services', [
            new CodeSample(
                <<<'CODE_SAMPLE'
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerTypeConverter(
    MySpecialTypeConverter::class
);
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
// Remove node and add or modify existing Services.yaml in Configuration/Services.yaml
CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @param Node[] $classStatements
     *
     * @return array<string, string|int>
     */
    private function collectServiceTags(array $classStatements): array
    {
        $collectServiceTags = [
            'name' => 'extbase.type_converter',
        ];

        $this->traverseNodesWithCallable($classStatements, function (Node $node) use (&$collectServiceTags) {
            if ($this->shouldSkipNodeOfTypeConverter($node)) {
                return null;
            }

            if (! $node instanceof ClassMethod || null === $node->stmts) {
                return null;
            }

            /** @var Node\Stmt\Return_[] $returns */
            $returns = $this->betterNodeFinder->findInstanceOf($node->stmts, Return_::class);

            $value = null;
            foreach ($returns as $return) {
                if (! $return->expr instanceof Expr) {
                    continue;
                }

                $value = $this->valueResolver->getValue($return->expr);
            }

            if ($this->isName($node->name, 'getPriority')) {
                $collectServiceTags['priority'] = $value;
            }

            if ($this->isName($node->name, 'getSupportedSourceTypes')) {
                $collectServiceTags['sources'] = implode(',', $value);
            }

            if ($this->isName($node->name, 'getSupportedTargetType')) {
                $collectServiceTags['target'] = $value;
            }
        });

        return $collectServiceTags;
    }

    private function shouldSkip(StaticCall $node): bool
    {
        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $node,
            new ObjectType('TYPO3\\CMS\\Extbase\\Utility\\ExtensionUtility')
        )) {
            return true;
        }

        if (! $this->nodeNameResolver->isName($node->name, 'registerTypeConverter')) {
            return true;
        }

        return ! isset($node->args[0]);
    }

    /**
     * @return array<mixed>
     */
    private function getYamlConfiguration(string $existingServicesYamlFilePath): array
    {
        if (file_exists($existingServicesYamlFilePath)) {
            return $this->symfonyYamlParser->parse(
                $existingServicesYamlFilePath,
                (string) file_get_contents($existingServicesYamlFilePath)
            );
        }

        $addedFilesWithContent = $this->removedAndAddedFilesCollector->getAddedFilesWithContent();
        foreach ($addedFilesWithContent as $addedFileWithContent) {
            if ($addedFileWithContent->getFilePath() === $existingServicesYamlFilePath) {
                return $this->symfonyYamlParser->parse(
                    $addedFileWithContent->getFilePath(),
                    $addedFileWithContent->getFileContent()
                );
            }
        }

        return [];
    }

    private function shouldSkipNodeOfTypeConverter(Node $node): bool
    {
        if (! $node instanceof ClassMethod) {
            return true;
        }

        return ! $this->nodeNameResolver->isNames(
            $node->name,
            ['getSupportedSourceTypes', 'getSupportedTargetType', 'getPriority']
        );
    }

    /**
     * @param Node\Stmt[] $classStatements
     */
    private function removeClassMethodsFromTypeConverter(string $fileName, array $classStatements): void
    {
        $nodeTraverser = new NodeTraverser();
        $visitor = new RemoveExtbaseTypeConverterNodeVisitor($this->nodeNameResolver);

        $nodeTraverser->addVisitor($visitor);
        $nodeTraverser->traverse($classStatements);

        $addedFileWithNodes = new AddedFileWithNodes($fileName, $classStatements);
        $content = $this->nodesWithFileDestinationPrinter->printNodesWithFileDestination($addedFileWithNodes);
        $this->removedAndAddedFilesCollector->addAddedFile(new AddedFileWithContent($fileName, $content));
    }
}

<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO311\v4;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Return_;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor;
use PHPStan\Type\ObjectType;
use Rector\Configuration\ConfigurationRuleFilter;
use Rector\Exception\ShouldNotHappenException;
use Rector\NodeTypeResolver\NodeScopeAndMetadataDecorator;
use Rector\NodeTypeResolver\PHPStan\Scope\PHPStanNodeScopeResolver;
use Rector\PhpParser\Node\FileNode;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\PhpParser\NodeTraverser\RectorNodeTraverser;
use Rector\PhpParser\Parser\RectorParser;
use Rector\Rector\AbstractRector;
use Rector\ValueObject\Application\File;
use Rector\ValueObject\Error\SystemError;
use Rector\VersionBonding\ComposerPackageConstraintFilter;
use Rector\VersionBonding\PhpVersionedFilter;
use Ssch\TYPO3Rector\Contract\FilesystemInterface;
use Ssch\TYPO3Rector\Filesystem\FilesFinder;
use Ssch\TYPO3Rector\PhpParser\Printer\PrettyTypo3Printer;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/11.4/Feature-94692-RegisteringIconsViaServiceContainer.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v11\v4\RegisterIconToIconFileRector\RegisterIconToIconFileRectorTest
 */
final class RegisterIconToIconFileRector extends AbstractRector implements DocumentedRuleInterface
{
    /**
     * @readonly
     */
    private FilesFinder $filesFinder;

    /**
     * @readonly
     */
    private FilesystemInterface $filesystem;

    /**
     * @readonly
     */
    private ValueResolver $valueResolver;

    /**
     * @readonly
     */
    private PrettyTypo3Printer $prettyTypo3Printer;

    /**
     * @readonly
     */
    private RectorParser $rectorParser;

    /**
     * @readonly
     */
    private NodeScopeAndMetadataDecorator $nodeScopeAndMetadataDecorator;

    /**
     * @readonly
     */
    private AddIconToReturnRector $addItemToReturnRector;

    private PHPStanNodeScopeResolver $phpStanNodeScopeResolver;

    private PhpVersionedFilter $phpVersionedFilter;

    private ComposerPackageConstraintFilter $composerPackageConstraintFilter;

    private ConfigurationRuleFilter $configurationRuleFilter;

    public function __construct(
        FilesFinder $filesFinder,
        FilesystemInterface $filesystem,
        ValueResolver $valueResolver,
        RectorParser $rectorParser,
        NodeScopeAndMetadataDecorator $nodeScopeAndMetadataDecorator,
        PrettyTypo3Printer $prettyTypo3Printer,
        AddIconToReturnRector $addItemToReturnRector,
        PHPStanNodeScopeResolver $phpStanNodeScopeResolver,
        PhpVersionedFilter $phpVersionedFilter,
        ComposerPackageConstraintFilter $composerPackageConstraintFilter,
        ConfigurationRuleFilter $configurationRuleFilter
    ) {
        $this->filesFinder = $filesFinder;
        $this->filesystem = $filesystem;
        $this->valueResolver = $valueResolver;
        $this->rectorParser = $rectorParser;
        $this->nodeScopeAndMetadataDecorator = $nodeScopeAndMetadataDecorator;
        $this->prettyTypo3Printer = $prettyTypo3Printer;
        $this->addItemToReturnRector = $addItemToReturnRector;
        $this->phpStanNodeScopeResolver = $phpStanNodeScopeResolver;
        $this->phpVersionedFilter = $phpVersionedFilter;
        $this->composerPackageConstraintFilter = $composerPackageConstraintFilter;
        $this->configurationRuleFilter = $configurationRuleFilter;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Generate or add registerIcon calls to Icons.php file', [new CodeSample(
            <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider;
use TYPO3\CMS\Core\Imaging\IconRegistry;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$iconRegistry = GeneralUtility::makeInstance(IconRegistry::class);
$iconRegistry->registerIcon(
    'mybitmapicon',
    BitmapIconProvider::class,
    [
        'source' => 'EXT:my_extension/Resources/Public/Icons/mybitmap.png',
    ]
);
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider;
use TYPO3\CMS\Core\Imaging\IconRegistry;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$iconRegistry = GeneralUtility::makeInstance(IconRegistry::class);

// Add Icons.php file
return [
    'mybitmapicon' => [
        'provider' => \TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class,
        'source' => 'EXT:my_extension/Resources/Public/Icons/mybitmap.png',
    ],
];
CODE_SAMPLE
        )]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Expression::class];
    }

    /**
     * @param Expression $node
     * @throws ShouldNotHappenException
     */
    public function refactor(Node $node): ?int
    {
        $methodCall = $node->expr;
        if (! $methodCall instanceof MethodCall) {
            return null;
        }

        if ($this->shouldSkip($methodCall)) {
            return null;
        }

        [$iconIdentifierString, $innerItems] = $this->createNewIconArray($methodCall);

        $directoryName = dirname($this->file->getFilePath());
        $iconsFilePath = $directoryName . '/Configuration/Icons.php';

        $newContent = $this->addNewIconToIconsFile($iconsFilePath, $iconIdentifierString, $innerItems);
        if ($newContent === null) {
            return null;
        }

        $this->filesystem->write($iconsFilePath, $newContent);

        return NodeVisitor::REMOVE_NODE;
    }

    private function shouldSkip(MethodCall $methodCall): bool
    {
        $args = $methodCall->getArgs();
        if (count($args) < 3) {
            return true;
        }

        if (! $this->isObjectType($methodCall->var, new ObjectType('TYPO3\CMS\Core\Imaging\IconRegistry'))) {
            return true;
        }

        if (! $this->isName($methodCall->name, 'registerIcon')) {
            return true;
        }

        return ! $this->filesFinder->isExtLocalConf($this->file->getFilePath());
    }

    /**
     * @return array<int, mixed>
     */
    private function createNewIconArray(MethodCall $methodCall): array
    {
        $args = $methodCall->getArgs();

        $iconIdentifier = $args[0]->value;
        $iconProvider = $args[1]->value;
        $options = $args[2]->value;

        $iconIdentifierString = $this->valueResolver->getValue($iconIdentifier);
        $iconProviderString = $this->valueResolver->getValue($iconProvider);

        $innerItems = [];
        $innerItems['provider'] = $iconProviderString;

        $optionsValue = $this->valueResolver->getValue($options);
        if (is_array($optionsValue)) {
            foreach ($optionsValue as $key => $value) {
                $innerItems[$key] = $value;
            }
        }

        return [$iconIdentifierString, $innerItems];
    }

    /**
     * @param array<string, mixed> $iconConfiguration
     * @throws ShouldNotHappenException
     */
    private function addNewIconToIconsFile(
        string $iconsFilePath,
        string $iconIdentifier,
        array $iconConfiguration
    ): ?string {
        $nodeTraverser = new NodeTraverser();

        if ($this->filesystem->fileExists($iconsFilePath)) {
            $existingIcons = $this->filesystem->read($iconsFilePath);
            $file = new File($iconsFilePath, $existingIcons);
            $parsingSystemError = $this->parseFileAndDecorateNodes($file);
            if ($parsingSystemError instanceof SystemError) {
                // we cannot process this file as the parsing and type resolving itself went wrong
                return null;
            }

            $nodes = $file->getNewStmts();
        } else {
            $return = new Return_($this->nodeFactory->createArray([]));
            $nodes = $this->phpStanNodeScopeResolver->processNodes([$return], 'php://temp');
        }

        $this->addItemToReturnRector->configure([
            AddIconToReturnRector::IDENTIFIER => $iconIdentifier,
            AddIconToReturnRector::OPTIONS => $iconConfiguration,
        ]);
        $nodeTraverser = new RectorNodeTraverser([
            $this->addItemToReturnRector,
        ], $this->phpVersionedFilter, $this->composerPackageConstraintFilter, $this->configurationRuleFilter);
        $nodes = $nodeTraverser->traverse($nodes);

        return $this->prettyTypo3Printer->prettyPrintFile($nodes);
    }

    /**
     * @see \Rector\Application\FileProcessor::parseFileAndDecorateNodes
     * @throws ShouldNotHappenException
     */
    private function parseFileAndDecorateNodes(File $file): ?SystemError
    {
        try {
            $this->parseFileNodes($file);
        } catch (ShouldNotHappenException $shouldNotHappenException) {
            throw $shouldNotHappenException;
        } catch (\Throwable $throwable) {
            return new SystemError($throwable->getMessage(), $file->getFilePath(), $throwable->getLine());
        }

        return null;
    }

    /**
     * @throws ShouldNotHappenException
     */
    private function parseFileNodes(File $file): void
    {
        // store tokens by original file content, so we don't have to print them right now
        $stmtsAndTokens = $this->rectorParser->parseFileContentToStmtsAndTokens($file->getOriginalFileContent());
        $oldStmts = $stmtsAndTokens->getStmts();
        $oldStmts = [new FileNode($oldStmts)];

        $oldTokens = $stmtsAndTokens->getTokens();
        $newStmts = $this->nodeScopeAndMetadataDecorator->decorateNodesFromFile($file->getFilePath(), $oldStmts);

        $file->hydrateStmtsAndTokens($newStmts, $oldStmts, $oldTokens);
    }
}

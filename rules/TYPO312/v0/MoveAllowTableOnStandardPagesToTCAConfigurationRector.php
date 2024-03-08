<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO312\v0;

use Nette\Utils\FileSystem;
use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Stmt\Expression;
use PhpParser\NodeTraverser;
use PhpParser\Parser;
use PhpParser\ParserFactory;
use PHPStan\Type\ObjectType;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\PhpParser\Printer\FormatPerservingPrinter;
use Rector\Rector\AbstractRector;
use Rector\ValueObject\Application\File;
use Ssch\TYPO3Rector\Contract\FilesystemInterface;
use Ssch\TYPO3Rector\Filesystem\FilesFinder;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Deprecation-98487-ExtensionManagementUtilityallowTableOnStandardPages.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v0\MoveAllowTableOnStandardPagesToTCAConfigurationRector\MoveAllowTableOnStandardPagesToTCAConfigurationRectorTest
 */
final class MoveAllowTableOnStandardPagesToTCAConfigurationRector extends AbstractRector
{
    /**
     * @readonly
     */
    private FilesFinder $filesFinder;

    /**
     * @readonly
     */
    private ValueResolver $valueResolver;

    /**
     * @readonly
     */
    private Parser $phpParser;

    /**
     * @readonly
     */
    private IgnorePageTypeRestrictionRector $ignorePageTypeRestrictionRector;

    /**
     * @readonly
     */
    private FormatPerservingPrinter $formatPerservingPrinter;

    private FilesystemInterface $filesystem;

    public function __construct(FilesFinder $filesFinder, ValueResolver $valueResolver, IgnorePageTypeRestrictionRector $ignorePageTypeRestrictionRector, FormatPerservingPrinter $formatPerservingPrinter, FilesystemInterface $filesystem)
    {
        $this->filesFinder = $filesFinder;
        $this->valueResolver = $valueResolver;
        $parserFactory = new ParserFactory();
        $this->phpParser = $parserFactory->create(ParserFactory::ONLY_PHP7);
        $this->ignorePageTypeRestrictionRector = $ignorePageTypeRestrictionRector;
        $this->formatPerservingPrinter = $formatPerservingPrinter;
        $this->filesystem = $filesystem;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Move method ExtensionManagementUtility::allowTableOnStandardPages to TCA configuration',
            [new CodeSample(
                <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
ExtensionManagementUtility::allowTableOnStandardPages('my_table');
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
$GLOBALS['TCA']['my_table']['ctrl']['security']['ignorePageTypeRestriction']
CODE_SAMPLE
            )]
        );
    }

    public function getNodeTypes(): array
    {
        return [Expression::class];
    }

    /**
     * @param Expression $node
     */
    public function refactor(Node $node)
    {
        $staticMethodCall = $node->expr;

        if (! $staticMethodCall instanceof Node\Expr\StaticCall) {
            return null;
        }

        if ($this->shouldSkip($staticMethodCall)) {
            return null;
        }

        $tableArgument = $staticMethodCall->args[0] ?? null;

        if ($tableArgument === null) {
            return null;
        }

        $tableName = $this->valueResolver->getValue($tableArgument);

        $directoryName = dirname($this->file->getFilePath());

        $pathToExistingConfigurationFile = $directoryName . '/Configuration/TCA/' . $tableName . '.php';

        if ($this->filesystem->fileExists($pathToExistingConfigurationFile)) {
            $this->addIgnorePageTypeRestrictionIfNeeded($pathToExistingConfigurationFile, $tableName);
        } else {
            $newConfigurationFile = $directoryName . '/Configuration/TCA/Overrides/' . $tableName . '.php';

            $content = sprintf(
                '$GLOBALS[\'TCA\'][\'%s\'][\'ctrl\'][\'security\'][\'ignorePageTypeRestriction\'] = true;',
                $tableName
            );
            $this->filesystem->write($newConfigurationFile, <<<CODE
<?php

{$content}

CODE
            );
        }

        return NodeTraverser::REMOVE_NODE;
    }

    private function shouldSkip(StaticCall $staticMethodCall): bool
    {
        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $staticMethodCall,
            new ObjectType('TYPO3\CMS\Core\Utility\ExtensionManagementUtility')
        )) {
            return true;
        }

        if (! $this->isName($staticMethodCall->name, 'allowTableOnStandardPages')) {
            return true;
        }

        return ! $this->filesFinder->isExtTables($this->file->getFilePath());
    }

    private function addIgnorePageTypeRestrictionIfNeeded(
        string $pathToExistingConfigurationFile,
        string $tableName
    ): void {
        $existingConfigurationFile = new File($pathToExistingConfigurationFile, FileSystem::read(
            $pathToExistingConfigurationFile
        ));
        $nodes = $this->phpParser->parse($existingConfigurationFile->getFileContent());

        if ($nodes === null) {
            return;
        }

        $nodeTraverser = new NodeTraverser();
        $this->ignorePageTypeRestrictionRector->configure([
            IgnorePageTypeRestrictionRector::TABLE_CONFIGURATION => $tableName,
        ]);

        $nodeTraverser->addVisitor($this->ignorePageTypeRestrictionRector);
        $newStmts = $nodeTraverser->traverse($nodes);
        $existingConfigurationFile->changeHasChanged(true);
        $existingConfigurationFile->changeNewStmts($newStmts);
        $newContent = $this->formatPerservingPrinter->printParsedStmstAndTokensToString($existingConfigurationFile);
        $existingConfigurationFile->changeFileContent($newContent);

        $this->filesystem->write($pathToExistingConfigurationFile, $newContent);
    }
}

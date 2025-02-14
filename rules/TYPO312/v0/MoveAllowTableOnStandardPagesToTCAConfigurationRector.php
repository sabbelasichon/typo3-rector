<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO312\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Stmt\Expression;
use PhpParser\NodeTraverser;
use PHPStan\Type\ObjectType;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\Rector\AbstractRector;
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
    private FilesystemInterface $filesystem;

    public function __construct(FilesFinder $filesFinder, ValueResolver $valueResolver, FilesystemInterface $filesystem)
    {
        $this->filesFinder = $filesFinder;
        $this->valueResolver = $valueResolver;
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

        if (! $staticMethodCall instanceof StaticCall) {
            return null;
        }

        if ($this->shouldSkip($staticMethodCall)) {
            return null;
        }

        $tableArgument = $staticMethodCall->args[0] ?? null;

        if ($tableArgument === null) {
            return null;
        }

        $tableNames = $this->valueResolver->getValue($tableArgument);

        foreach (explode(',', $tableNames) as $tableName) {
            $tableName = trim($tableName);
            if ($tableName === '') {
                continue;
            }

            $directoryName = dirname($this->file->getFilePath());
            $newConfigurationFile = $directoryName . '/Configuration/TCA/Overrides/' . $tableName . '.php';
            $this->writeConfigurationToFile($newConfigurationFile, $tableName);
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

    private function writeConfigurationToFile(string $newConfigurationFile, string $tableName): void
    {
        $content = sprintf(
            '$GLOBALS[\'TCA\'][\'%s\'][\'ctrl\'][\'security\'][\'ignorePageTypeRestriction\'] = true;',
            $tableName
        );
        if ($this->filesystem->fileExists($newConfigurationFile)) {
            $this->filesystem->appendToFile($newConfigurationFile, $content . PHP_EOL);
        } else {
            $this->filesystem->write($newConfigurationFile, <<<CODE
<?php

{$content}

CODE
            );
        }
    }
}

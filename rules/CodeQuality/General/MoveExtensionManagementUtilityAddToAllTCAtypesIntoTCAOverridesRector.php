<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\CodeQuality\General;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Expression;
use PhpParser\NodeVisitor;
use PHPStan\Type\ObjectType;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\PhpParser\Printer\BetterStandardPrinter;
use Rector\Rector\AbstractRector;
use Ssch\TYPO3Rector\Contract\FilesystemInterface;
use Ssch\TYPO3Rector\Filesystem\FilesFinder;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://review.typo3.org/c/Packages/TYPO3.CMS/+/52437
 * @see \Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\MoveExtensionManagementUtilityAddToAllTCAtypesIntoTCAOverridesRector\MoveExtensionManagementUtilityAddToAllTCAtypesIntoTCAOverridesRectorTest
 */
class MoveExtensionManagementUtilityAddToAllTCAtypesIntoTCAOverridesRector extends AbstractRector implements DocumentedRuleInterface
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
    private BetterStandardPrinter $betterStandardPrinter;

    public function __construct(
        FilesFinder $filesFinder,
        FilesystemInterface $filesystem,
        ValueResolver $valueResolver,
        BetterStandardPrinter $betterStandardPrinter
    ) {
        $this->filesFinder = $filesFinder;
        $this->filesystem = $filesystem;
        $this->valueResolver = $valueResolver;
        $this->betterStandardPrinter = $betterStandardPrinter;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Move `ExtensionManagementUtility::addToAllTCAtypes()` into table specific Configuration/TCA/Overrides file',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('table', 'new_field', '', 'after:existing_field');
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
// Move to table specific Configuration/TCA/Overrides/table.php file
CODE_SAMPLE
                ),
            ]
        );
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

        $tableNameArgument = $staticMethodCall->args[0] ?? null;
        if ($tableNameArgument === null) {
            return null;
        }

        $tableNameValue = $tableNameArgument->value;
        if (! $tableNameValue instanceof String_ && ! $tableNameValue instanceof Variable) {
            return null;
        }

        $resolvedTableName = $this->resolveTableName($tableNameValue);
        if ($resolvedTableName instanceof String_) {
            $staticMethodCall->args[0] = $resolvedTableName;
            $tableNameAsString = $resolvedTableName->value;
        } else {
            $tableNameAsString = 'unknown';
        }

        $content = $this->betterStandardPrinter->prettyPrint([$staticMethodCall]) . ';';

        $directoryName = dirname($this->file->getFilePath());
        $newConfigurationFile = $directoryName . '/Configuration/TCA/Overrides/' . $tableNameAsString . '.php';
        if ($this->filesystem->fileExists($newConfigurationFile)) {
            $this->filesystem->appendToFile($newConfigurationFile, $content . PHP_EOL);
        } else {
            $this->filesystem->write($newConfigurationFile, <<<CODE
<?php

{$content}

CODE
            );
        }

        return NodeVisitor::REMOVE_NODE;
    }

    private function shouldSkip(StaticCall $staticMethodCall): bool
    {
        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $staticMethodCall,
            new ObjectType('TYPO3\CMS\Core\Utility\ExtensionManagementUtility')
        )) {
            return true;
        }

        if (! $this->isName($staticMethodCall->name, 'addToAllTCAtypes')) {
            return true;
        }

        return ! $this->filesFinder->isExtTables($this->file->getFilePath());
    }

    /**
     * @param Variable|String_ $contentArgumentValue
     */
    private function resolveTableName($contentArgumentValue): ?String_
    {
        if ($contentArgumentValue instanceof String_) {
            return $contentArgumentValue;
        }

        if (! $contentArgumentValue instanceof Variable) {
            return null;
        }

        $tableName = $this->valueResolver->getValue($contentArgumentValue);

        return new String_($tableName);
    }
}

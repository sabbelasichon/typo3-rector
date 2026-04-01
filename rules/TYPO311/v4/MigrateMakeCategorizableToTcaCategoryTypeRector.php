<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO311\v4;

use PhpParser\Node;
use PhpParser\Node\ArrayItem;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Nop;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/11.4/Deprecation-85613-CategoryRegistry.html
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/11.4/Feature-94622-NewTCATypeCategory.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v11\v4\MigrateMakeCategorizableToTcaCategoryTypeRector\MigrateMakeCategorizableToTcaCategoryTypeRectorTest
 */
final class MigrateMakeCategorizableToTcaCategoryTypeRector extends AbstractRector implements DocumentedRuleInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Migrate ExtensionManagementUtility::makeCategorizable() to TCA type "category"',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
ExtensionManagementUtility::makeCategorizable('my_extension', 'tx_myextension_table');
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

$GLOBALS['TCA']['tx_myextension_table']['columns']['categories'] = [
    'config' => [
        'type' => 'category',
    ],
];
ExtensionManagementUtility::addToAllTCAtypes('tx_myextension_table', 'categories');
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
     * @return Node[]|null
     */
    public function refactor(Node $node): ?array
    {
        $staticCall = $node->expr;

        if (! $staticCall instanceof StaticCall) {
            return null;
        }

        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $staticCall,
            new ObjectType('TYPO3\CMS\Core\Utility\ExtensionManagementUtility')
        )) {
            return null;
        }

        if (! $this->isName($staticCall->name, 'makeCategorizable')) {
            return null;
        }

        $tableNameArg = $staticCall->args[1] ?? null;
        if ($tableNameArg === null) {
            return null;
        }

        $tableNameNode = $tableNameArg->value;

        $fieldNameArg = $staticCall->args[2] ?? null;
        $fieldNameNode = $fieldNameArg !== null ? $fieldNameArg->value : new String_('categories');

        // Build: $GLOBALS['TCA'][$tableName]['columns'][$fieldName] = ['config' => ['type' => 'category']];
        $globalsTca = new ArrayDimFetch(new Variable('GLOBALS'), new String_('TCA'));
        $globalsTcaTable = new ArrayDimFetch($globalsTca, $tableNameNode);
        $globalsTcaTableColumns = new ArrayDimFetch($globalsTcaTable, new String_('columns'));
        $globalsTcaTableColumnsField = new ArrayDimFetch($globalsTcaTableColumns, $fieldNameNode);

        $configArray = new Array_([new ArrayItem(new String_('category'), new String_('type'))]);
        $columnArray = new Array_([new ArrayItem($configArray, new String_('config'))]);

        $tcaAssignment = new Expression(new Assign($globalsTcaTableColumnsField, $columnArray));

        // Build: ExtensionManagementUtility::addToAllTCAtypes($tableName, $fieldName);
        $addToAllTCAtypesCall = $this->nodeFactory->createStaticCall(
            'TYPO3\CMS\Core\Utility\ExtensionManagementUtility',
            'addToAllTCAtypes',
            [$tableNameNode, $fieldNameNode]
        );
        $addToAllTCAtypesStatement = new Expression($addToAllTCAtypesCall);

        return [new Nop(), $tcaAssignment, $addToAllTCAtypesStatement];
    }
}

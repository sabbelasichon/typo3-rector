<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO313\v2;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\String_;
use Rector\Rector\AbstractRector;
use Ssch\TYPO3Rector\Helper\ArrayUtility;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/13.2/Deprecation-104108-TableDependantDefinitionOfColumnsOnly.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v13\v2\MigrateTableDependentDefinitionOfColumnsOnlyRector\MigrateTableDependentDefinitionOfColumnsOnlyRectorTest
 */
final class MigrateTableDependentDefinitionOfColumnsOnlyRector extends AbstractRector implements DocumentedRuleInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate table dependant definition of columnsOnly', [new CodeSample(
            <<<'CODE_SAMPLE'
$urlParameters = [
    'edit' => [
        'pages' => [
            1 => 'edit',
        ],
    ],
    'columnsOnly' => 'title,slug'
    'returnUrl' => $request->getAttribute('normalizedParams')->getRequestUri(),
];

GeneralUtility::makeInstance(UriBuilder::class)->buildUriFromRoute('record_edit', $urlParameters);
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
$urlParameters = [
    'edit' => [
        'pages' => [
            1 => 'edit',
        ],
    ],
    'columnsOnly' => [
        'pages' => [
            'title',
            'slug'
        ]
    ],
    'returnUrl' => $request->getAttribute('normalizedParams')->getRequestUri(),
];

GeneralUtility::makeInstance(UriBuilder::class)->buildUriFromRoute('record_edit', $urlParameters);
CODE_SAMPLE
        )]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Array_::class];
    }

    /**
     * @param Array_ $node
     */
    public function refactor(Node $node): ?Node
    {
        $editItemNode = null;
        $columnsOnlyItemNode = null;
        $columnsOnlyItemIndex = null;

        foreach ($node->items as $index => $item) {
            if (! $item instanceof ArrayItem) {
                continue;
            }

            if ($item->key instanceof String_ && $item->key->value === 'columnsOnly') {
                if (! $item->value instanceof Array_) {
                    $columnsOnlyItemNode = $item;
                    $columnsOnlyItemIndex = $index;
                }
            } elseif ($item->key instanceof String_ && $item->key->value === 'edit' && $item->value instanceof Array_) {
                $editItemNode = $item;
            }
        }

        // Ensure both 'edit' and 'columnsOnly' (as non-array) are found
        if (! $editItemNode instanceof ArrayItem
            || ! $columnsOnlyItemNode instanceof ArrayItem
            || $columnsOnlyItemIndex === null
        ) {
            return null;
        }

        /** @var Array_ $editArrayNode */
        $editArrayNode = $editItemNode->value;

        // Get the first key node from the 'edit' array's content.
        // This key can be a String_ or a Variable.
        /** @var String_|Variable|null $dynamicKeyNodeFromEdit */
        $dynamicKeyNodeFromEdit = null;
        if (isset($editArrayNode->items[0]) && $editArrayNode->items[0] instanceof ArrayItem) {
            $keyNode = $editArrayNode->items[0]->key;
            if ($keyNode instanceof String_ || $keyNode instanceof Variable) {
                $dynamicKeyNodeFromEdit = $keyNode;
            }
        }

        if ($dynamicKeyNodeFromEdit === null) {
            return null;
        }

        /** @var Expr $originalColumnsOnlyValueNode */
        $originalColumnsOnlyValueNode = $columnsOnlyItemNode->value;

        $innerArrayItems = [];
        if ($originalColumnsOnlyValueNode instanceof String_) {
            $columnsOnlyValueString = $originalColumnsOnlyValueNode->value;
            if (trim($columnsOnlyValueString) !== '') {
                $columnsArray = ArrayUtility::trimExplode(',', $columnsOnlyValueString);
                foreach ($columnsArray as $column) {
                    $innerArrayItems[] = new ArrayItem(new String_($column));
                }
            }
        } else {
            $innerArrayItems[] = new ArrayItem($originalColumnsOnlyValueNode);
        }

        $innerArray = new Array_($innerArrayItems);

        $node->items[$columnsOnlyItemIndex] = new ArrayItem(
            new Array_([new ArrayItem($innerArray, $dynamicKeyNodeFromEdit)]),
            new String_('columnsOnly')
        );

        return $node;
    }
}

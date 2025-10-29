<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO312\v3;

use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Scalar\Int_;
use PhpParser\Node\Scalar\String_;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.3/Deprecation-99739-IndexedArrayKeysForTCAItems.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v3\MigrateExtensionManagementUtilityAddTcaSelectItemRector\MigrateExtensionManagementUtilityAddTcaSelectItemRectorTest
 */
final class MigrateExtensionManagementUtilityAddTcaSelectItemRector extends AbstractRector implements DocumentedRuleInterface
{
    /**
     * @var array<int, string>
     */
    private const KEY_MAPPING = ['label', 'value', 'icon', 'group', 'description'];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate `ExtensionManagementUtility::addTcaSelectItem()`', [new CodeSample(
            <<<'CODE_SAMPLE'
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItem($table, $field, [
    'My Content Element',
    'my_content_element',
    'my-icon-identifier',
    'group1',
    'My Description',
]);
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItem($table, $field, [
    'label' => 'My Content Element',
    'value' => 'my_content_element',
    'icon' => 'my-icon-identifier',
    'group' => 'group1',
    'description' => 'My Description',
]);
CODE_SAMPLE
        )]);
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
        if (! $this->isName($node->class, 'TYPO3\CMS\Core\Utility\ExtensionManagementUtility')) {
            return null;
        }

        if (! $this->isName($node->name, 'addTcaSelectItem')) {
            return null;
        }

        $args = $node->getArgs();
        if (count($args) < 3) {
            return null;
        }

        $itemArrayNode = $args[2]->value;

        if (! $itemArrayNode instanceof Array_) {
            return null;
        }

        if ($itemArrayNode->items === []) {
            return null;
        }

        // Check if the array is already refactored (has string keys)
        $firstItem = $itemArrayNode->items[0];
        if ($firstItem === null) {
            return null;
        }

        // If the first key is a string (e.g., 'label'), it's already associative
        if ($firstItem->key instanceof String_) {
            return null;
        }

        // If the first key is not null (implicit 0) and not an Int_ 0,
        // it's some other structure we shouldn't touch (e.g., constant key or starting index != 0).
        if ($firstItem->key !== null && (! $firstItem->key instanceof Int_ || $firstItem->key->value !== 0)) {
            return null;
        }

        $newItems = [];
        $hasChanged = false;

        foreach ($itemArrayNode->items as $index => $item) {
            if ($item === null) {
                continue;
            }

            // Check if a mapping exists for this numeric index
            if (! isset(self::KEY_MAPPING[$index])) {
                // No mapping for this index, add the item as-is
                $newItems[] = $item;
                continue;
            }

            // Check if the item is truly indexed (key is null or matches the numeric index)
            $isIndexed = $item->key === null || ($item->key instanceof Int_ && $item->key->value === $index);

            if ($isIndexed) {
                $newKey = new String_(self::KEY_MAPPING[$index]);
                // Create a new ArrayItem, preserving the original value and attributes
                $newItems[] = new ArrayItem($item->value, $newKey, $item->byRef, $item->getAttributes());
                $hasChanged = true;
            } else {
                // This item has a key that is not its numeric index (e.g., a string key).
                // Add it as-is to preserve it.
                $newItems[] = $item;
            }
        }

        // If no changes were made, return null
        if (! $hasChanged) {
            return null;
        }

        $itemArrayNode->items = $newItems;

        return $node;
    }
}

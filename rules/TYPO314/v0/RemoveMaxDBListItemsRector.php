<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO314\v0;

use PhpParser\Node;
use PhpParser\Node\ArrayItem;
use PhpParser\Node\Expr\Array_;
use Ssch\TYPO3Rector\Rector\AbstractTcaRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/14.0/Breaking-106412-TCAInterfaceSettingsForListViewRemoved.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v14\v0\RemoveMaxDBListItemsRector\RemoveMaxDBListItemsRectorTest
 */
final class RemoveMaxDBListItemsRector extends AbstractTcaRector implements DocumentedRuleInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove $TCA[$mytable][\'interface\'][\'maxDBListItems\'], \'maxSingleDBListItems\'',
            [
            new CodeSample(
                <<<'CODE_SAMPLE'
return [
    'columns' => [],
    'interface' => [
        'maxDBListItems' => 'foo',
        'maxSingleDBListItems' => 'foo',
    ],
];
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
return [
    'columns' => [],
];
CODE_SAMPLE
            ),

        ]);
    }

    protected function refactorInterface(Array_ $interfaceArray, Node $node): void
    {
        $remainingInterfaceItems = count($interfaceArray->items);

        $maxDBListItemsArrayItem = $this->extractArrayItemByKey($interfaceArray, 'maxDBListItems');
        if ($maxDBListItemsArrayItem instanceof ArrayItem) {
            $this->removeArrayItemFromArrayByKey($interfaceArray, 'maxDBListItems');
            --$remainingInterfaceItems;
        }

        $maxSingleDBListItemsArrayItem = $this->extractArrayItemByKey($interfaceArray, 'maxSingleDBListItems');
        if ($maxSingleDBListItemsArrayItem instanceof ArrayItem) {
            $this->removeArrayItemFromArrayByKey($interfaceArray, 'maxSingleDBListItems');
            --$remainingInterfaceItems;
        }

        if ($remainingInterfaceItems === 0 && $node instanceof Array_) {
            $this->removeArrayItemFromArrayByKey($node, 'interface');
        }
    }
}

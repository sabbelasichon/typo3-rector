<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO310\v3;

use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use Ssch\TYPO3Rector\Rector\AbstractTcaRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/10.3/Feature-88901-RenderAllFieldsInElementInformationController.html?highlight=showrecordfieldlist
 * @see \Ssch\TYPO3Rector\Tests\Rector\v10\v3\RemoveShowRecordFieldListInsideInterfaceSectionRector\RemoveShowRecordFieldListInsideInterfaceSectionRectorTest
 */
final class RemoveShowRecordFieldListInsideInterfaceSectionRector extends AbstractTcaRector implements DocumentedRuleInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Remove showRecordFieldList inside section interface', [
            new CodeSample(
                <<<'CODE_SAMPLE'
return [
    'ctrl' => [
    ],
    'interface' => [
        'showRecordFieldList' => 'foo,bar,baz',
    ],
    'columns' => [
    ],
];
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
return [
    'ctrl' => [
    ],
    'columns' => [
    ],
];
CODE_SAMPLE
            ),
        ]);
    }

    protected function refactorInterface(Array_ $interfaceArray, Node $node): void
    {
        $this->removeArrayItemFromArrayByKey($interfaceArray, 'showRecordFieldList');

        $remainingInterfaceItems = count($interfaceArray->items);
        if ($remainingInterfaceItems === 0 && $node instanceof Array_) {
            $this->removeArrayItemFromArrayByKey($node, 'interface');
        }
    }
}

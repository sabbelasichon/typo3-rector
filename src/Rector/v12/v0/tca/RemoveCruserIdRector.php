<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v12\v0\tca;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use Ssch\TYPO3Rector\Rector\AbstractTcaRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Breaking-98024-TCA-option-cruserid-removed.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v0\tca\RemoveCruserIdRector\RemoveCruserIdRectorTest
 */
final class RemoveCruserIdRector extends AbstractTcaRector
{
    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Remove the TCA option cruser_id', [new CodeSample(
            <<<'CODE_SAMPLE'
return [
    'ctrl' => [
        'label' => 'foo',
        'cruser_id' => 'cruser_id',
    ],
    'columns' => [
    ],
];
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
return [
    'ctrl' => [
        'label' => 'foo',
    ],
    'columns' => [
    ],
];
CODE_SAMPLE
        )]);
    }

    protected function refactorCtrl(Array_ $ctrlArray): void
    {
        foreach ($ctrlArray->items as $ctrlItemKey => $ctrlItem) {
            if (! $ctrlItem instanceof ArrayItem) {
                continue;
            }

            if (! $ctrlItem->key instanceof Expr) {
                continue;
            }

            if ($this->valueResolver->isValue($ctrlItem->key, 'cruser_id')) {
                unset($ctrlArray->items[$ctrlItemKey]);
                $this->hasAstBeenChanged = true;
                break;
            }
        }
    }
}

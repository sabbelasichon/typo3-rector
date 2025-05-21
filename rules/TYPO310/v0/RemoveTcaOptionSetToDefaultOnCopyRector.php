<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO310\v0;

use PhpParser\Node\Expr\Array_;
use Ssch\TYPO3Rector\Rector\AbstractTcaRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/10.0/Breaking-87989-TCAOptionSetToDefaultOnCopyRemoved.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v10\v0\RemoveTcaOptionSetToDefaultOnCopyRector\RemoveTcaOptionSetToDefaultOnCopyRectorTest
 */
final class RemoveTcaOptionSetToDefaultOnCopyRector extends AbstractTcaRector implements DocumentedRuleInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Remove TCA option "setToDefaultOnCopy"', [new CodeSample(
            <<<'CODE_SAMPLE'
return [
    'ctrl' => [
        'selicon_field' => 'icon',
        'setToDefaultOnCopy' => 'foo'
    ],
    'columns' => [
    ],
];
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
return [
    'ctrl' => [
        'selicon_field' => 'icon'
    ],
    'columns' => [
    ],
];
CODE_SAMPLE
        )]);
    }

    protected function refactorCtrl(Array_ $ctrlArray): void
    {
        $this->removeArrayItemFromArrayByKey($ctrlArray, 'setToDefaultOnCopy');
    }
}

<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO310\v0;

use PhpParser\Node\Expr\Array_;
use Ssch\TYPO3Rector\Rector\AbstractTcaRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/10.0/Breaking-87989-TCAOptionSetToDefaultOnCopyRemoved.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v10\v0\RemoveTcaOptionSetToDefaultOnCopyRector\RemoveTcaOptionSetToDefaultOnCopyRectorTest
 */
final class RemoveTcaOptionSetToDefaultOnCopyRector extends AbstractTcaRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('TCA option setToDefaultOnCopy removed', [new CodeSample(
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
        $hasAstBeenChanged = false;
        if ($this->removeArrayItemFromArrayByKey($ctrlArray, 'setToDefaultOnCopy')) {
            $hasAstBeenChanged = true;
        }

        $this->hasAstBeenChanged = $hasAstBeenChanged;
    }
}

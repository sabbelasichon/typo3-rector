<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO310\v0;

use PhpParser\Node\Expr\Array_;
use Ssch\TYPO3Rector\Rector\AbstractTcaRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/10.0/Breaking-87937-TCAOption_selicon_field_path_removed.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v10\v0\RemoveSeliconFieldPathRector\RemoveSeliconFieldPathRectorTest
 */
final class RemoveSeliconFieldPathRector extends AbstractTcaRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('TCA option "selicon_field_path" removed', [new CodeSample(
            <<<'CODE_SAMPLE'
return [
    'ctrl' => [
        'selicon_field' => 'icon',
        'selicon_field_path' => 'uploads/media'
    ],
];
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
return [
    'ctrl' => [
        'selicon_field' => 'icon',
    ],
];
CODE_SAMPLE
        )]);
    }

    protected function refactorCtrl(Array_ $ctrlArray): void
    {
        $hasAstBeenChanged = false;
        if ($this->removeArrayItemFromArrayByKey($ctrlArray, 'selicon_field_path')) {
            $hasAstBeenChanged = true;
        }
        $this->hasAstBeenChanged = $hasAstBeenChanged;
    }
}

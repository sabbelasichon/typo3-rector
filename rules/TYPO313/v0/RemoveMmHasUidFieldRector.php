<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO313\v0;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use Ssch\TYPO3Rector\Rector\AbstractTcaRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/13.0/Deprecation-101554-ObsoleteTCAMM_hasUidField.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v13\v0\RemoveMmHasUidFieldRector\RemoveMmHasUidFieldRectorTest
 */
final class RemoveMmHasUidFieldRector extends AbstractTcaRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Unset the value in the config mmHasUidField', [new CodeSample(
            <<<'CODE_SAMPLE'
return [
    'columns' => [
        'nullable_column' => [
            'config' => [
                'type' => 'group',
                'MM_hasUidField' => false,
            ],
        ],
    ],
];
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
return [
    'columns' => [
        'nullable_column' => [
            'config' => [
                'type' => 'group',
            ],
        ],
    ],
];
CODE_SAMPLE
        )]);
    }

    protected function refactorColumn(Expr $columnName, Expr $columnTca): void
    {
        $configArray = $this->extractSubArrayByKey($columnTca, self::CONFIG);
        if (! $configArray instanceof Array_) {
            return;
        }

        if (! $this->hasKey($configArray, 'MM_hasUidField')) {
            return;
        }

        $this->removeArrayItemFromArrayByKey($configArray, 'MM_hasUidField');

        $this->hasAstBeenChanged = true;
    }
}

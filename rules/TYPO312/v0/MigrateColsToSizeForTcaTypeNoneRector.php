<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO312\v0;

use PhpParser\Node\ArrayItem;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Scalar\String_;
use Ssch\TYPO3Rector\Rector\AbstractTcaRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Deprecation-97109-TCATypeNoneColsOption.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v0\tca\MigrateColsToSizeForTcaTypeNoneRector\MigrateColsToSizeForTcaTypeNoneRectorTest
 */
final class MigrateColsToSizeForTcaTypeNoneRector extends AbstractTcaRector implements DocumentedRuleInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrates option cols to size for TCA type none', [new CodeSample(
            <<<'CODE_SAMPLE'
return [
    'columns' => [
        'aColumn' => [
            'config' => [
                'type' => 'none',
                'cols' => 20,
            ],
        ],
    ],
];
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
return [
    'columns' => [
        'aColumn' => [
            'config' => [
                'type' => 'none',
                'size' => 20,
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

        if (! $this->isConfigType($configArray, 'none')) {
            return;
        }

        $arrayItemToChange = $this->extractArrayItemByKey($configArray, 'cols');
        if (! $arrayItemToChange instanceof ArrayItem) {
            return;
        }

        $this->removeArrayItemFromArrayByKey($configArray, 'size');

        $arrayItemToChange->key = new String_('size');

        $this->hasAstBeenChanged = true;
    }
}

<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO314\v0;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use Ssch\TYPO3Rector\Rector\AbstractTcaRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/14.0/Breaking-106976-RemovalOfTCASearchFieldConfigurationOptions.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v14\v0\RemoveFieldSearchConfigOptionsRector\RemoveFieldSearchConfigOptionsRectorTest
 */
final class RemoveFieldSearchConfigOptionsRector extends AbstractTcaRector implements DocumentedRuleInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Remove TCA search field configuration options', [new CodeSample(
            <<<'CODE_SAMPLE'
return [
    'columns' => [
        'my_field' => [
            'config' => [
                'type' => 'input',
                'search' => [
                    'case' => true,
                    'pidonly' => true,
                    'andWhere' => '{#CType}=\'text\'',
                ],
            ],
        ],
    ],
];
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
return [
    'columns' => [
        'my_field' => [
            'config' => [
                'type' => 'input',
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

        if (! $this->hasKey($configArray, 'search')) {
            return;
        }

        $this->removeArrayItemFromArrayByKey($configArray, 'search');
    }
}

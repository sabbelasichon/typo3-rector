<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO311\v4;

use PhpParser\Node\ArrayItem;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Scalar\String_;
use Ssch\TYPO3Rector\Rector\AbstractTcaRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/11.4/Deprecation-95037-RootUidRelatedSettingOfTrees.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v11\v4\MigrateRootUidToStartingPointsRector\MigrateRootUidToStartingPointsRectorTest
 */
final class MigrateRootUidToStartingPointsRector extends AbstractTcaRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'If a column has [treeConfig][rootUid] defined, migrate to [treeConfig][startingPoints] on the same level.',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
return [
    'columns' => [
        'aField' => [
            'config' => [
                'type' => 'select',
                'renderType' => 'selectTree',
                'treeConfig' => [
                    'rootUid' => 42
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
        'aField' => [
            'config' => [
                'type' => 'select',
                'renderType' => 'selectTree',
                'treeConfig' => [
                    'startingPoints' => '42'
                ],
            ],
        ],
    ],
];
CODE_SAMPLE
                ),

            ]
        );
    }

    protected function refactorColumn(Expr $columnName, Expr $columnTca): void
    {
        $configArray = $this->extractSubArrayByKey($columnTca, self::CONFIG);
        if (! $configArray instanceof Array_) {
            return;
        }

        // Fetch type
        if (! $this->isConfigType($configArray, 'select') && ! $this->isConfigType($configArray, 'category')) {
            return;
        }

        $treeConfigArray = $this->extractSubArrayByKey($configArray, 'treeConfig');
        if (! $treeConfigArray instanceof Array_) {
            return;
        }

        if ($this->hasKey($configArray, 'treeConfig')) {
            $treeConfigArrayItem = $this->extractArrayItemByKey($configArray, 'treeConfig');
            if (! $treeConfigArrayItem instanceof ArrayItem) {
                return;
            }

            $rootUidArrayItem = $this->extractArrayItemByKey($treeConfigArray, 'rootUid');
            if (! $rootUidArrayItem instanceof ArrayItem) {
                return;
            }

            $rootUidValue = $this->valueResolver->getValue($rootUidArrayItem->value);

            if ($rootUidValue === 0) {
                return;
            }

            $treeConfigArray->items[] = new ArrayItem(new String_((string) $rootUidValue), new String_(
                'startingPoints'
            ));
        }

        $this->removeArrayItemFromArrayByKey($treeConfigArray, 'rootUid');

        $this->hasAstBeenChanged = true;
    }
}

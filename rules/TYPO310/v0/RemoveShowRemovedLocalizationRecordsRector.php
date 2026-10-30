<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO310\v0;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use Rector\RuleDoc\CodeSample\CodeSample;
use Rector\RuleDoc\Contract\DocumentedRuleInterface;
use Rector\RuleDoc\RuleDefinition;
use Ssch\TYPO3Rector\Contract\NoChangelogRequiredInterface;
use Ssch\TYPO3Rector\Rector\AbstractTcaRector;

/**
 * @see \Ssch\TYPO3Rector\Tests\Rector\v10\v0\RemoveShowRemovedLocalizationRecordsRector\RemoveShowRemovedLocalizationRecordsRectorTest
 */
final class RemoveShowRemovedLocalizationRecordsRector extends AbstractTcaRector implements NoChangelogRequiredInterface, DocumentedRuleInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Remove showRemovedLocalizationRecords from inline TCA configuration', [
            new CodeSample(
                <<<'CODE_SAMPLE'
return [
    'columns' => [
        'falFileRelation' => [
            'config' => [
                'type' => 'inline',
                'appearance' => [
                    'showPossibleLocalizationRecords' => false,
                    'showRemovedLocalizationRecords' => false,
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
        'falFileRelation' => [
            'config' => [
                'type' => 'inline',
                'appearance' => [
                    'showPossibleLocalizationRecords' => false,
                ],
            ],
        ],
    ],
];
CODE_SAMPLE
            ),
        ]);
    }

    protected function refactorColumn(Expr $columnName, Expr $columnTca): void
    {
        $configArray = $this->extractSubArrayByKey($columnTca, self::CONFIG);
        if (! $configArray instanceof Array_) {
            return;
        }

        if (! $this->isConfigType($configArray, 'inline')) {
            return;
        }

        if (! $this->hasKey($configArray, 'appearance')) {
            return;
        }

        $appearanceArray = $this->extractSubArrayByKey($configArray, 'appearance');
        if (! $appearanceArray instanceof Array_) {
            return;
        }

        if (! $this->hasKey($appearanceArray, 'showRemovedLocalizationRecords')) {
            return;
        }

        $this->removeArrayItemFromArrayByKey($appearanceArray, 'showRemovedLocalizationRecords');
    }
}

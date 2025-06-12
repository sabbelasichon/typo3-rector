<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO314\v0;

use PhpParser\Node\ArrayItem;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Scalar\String_;
use Ssch\TYPO3Rector\Helper\ArrayUtility;
use Ssch\TYPO3Rector\Helper\StringUtility;
use Ssch\TYPO3Rector\Rector\AbstractTcaRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/14.0/Breaking-98070-RemoveEvalMethodYear.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v14\v0\RemoveEvalYearFlagRector\RemoveEvalYearFlagRectorTest
 */
final class RemoveEvalYearFlagRector extends AbstractTcaRector implements DocumentedRuleInterface
{
    /**
     * @var string
     */
    private const YEAR = 'year';

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Remove eval year flag', [new CodeSample(
            <<<'CODE_SAMPLE'
return [
    'columns' => [
        'year_column' => [
            'config' => [
                'eval' => 'trim,year',
            ],
        ],
    ],
];
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
return [
    'columns' => [
        'year_column' => [
            'config' => [
                'eval' => 'trim',
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

        if (! $this->hasKey($configArray, 'eval')) {
            return;
        }

        $evalArrayItem = $this->extractArrayItemByKey($configArray, 'eval');
        if (! $evalArrayItem instanceof ArrayItem) {
            return;
        }

        $evalListValue = $this->valueResolver->getValue($evalArrayItem->value);
        if (! is_string($evalListValue)) {
            return;
        }

        if (! StringUtility::inList($evalListValue, self::YEAR)) {
            return;
        }

        $evalList = ArrayUtility::trimExplode(',', $evalListValue, true);

        // Remove "year" from $evalList
        $evalList = array_filter($evalList, static fn (string $eval): bool => $eval !== self::YEAR);

        if ($evalList !== []) {
            // Write back filtered 'eval'
            $evalArrayItem->value = new String_(implode(',', $evalList));
        } else {
            // 'eval' is empty, remove whole configuration
            $this->removeArrayItemFromArrayByKey($configArray, 'eval');
        }

        $this->hasAstBeenChanged = true;
    }
}

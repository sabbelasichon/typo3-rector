<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v12\v0;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\String_;
use Ssch\TYPO3Rector\Helper\ArrayUtility;
use Ssch\TYPO3Rector\Helper\StringUtility;
use Ssch\TYPO3Rector\Rector\AbstractTcaRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Deprecation-97384-TCAOptionNullable.html
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Feature-97384-TCAOptionNullable.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v0\tca\MigrateNullFlagRector\MigrateNullFlagRectorTest
 */
final class MigrateNullFlagRector extends AbstractTcaRector
{
    /**
     * @var string
     */
    private const NULL = 'null';

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate null flag', [new CodeSample(
            <<<'CODE_SAMPLE'
return [
    'columns' => [
        'nullable_column' => [
            'config' => [
                'eval' => 'null',
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
                'nullable' => true,
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

        if (! StringUtility::inList($evalListValue, self::NULL)) {
            return;
        }

        $evalList = ArrayUtility::trimExplode(',', $evalListValue, true);

        // Remove "null" from $evalList
        $evalList = array_filter($evalList, static fn (string $eval) => $eval !== self::NULL);

        if ($evalList !== []) {
            // Write back filtered 'eval'
            $evalArrayItem->value = new String_(implode(',', $evalList));
        } else {
            // 'eval' is empty, remove whole configuration
            $this->removeArrayItemFromArrayByKey($configArray, 'eval');
        }

        $this->removeArrayItemFromArrayByKey($configArray, 'nullable');

        $configArray->items[] = new ArrayItem(new ConstFetch(new Name('true')), new String_('nullable'));

        $this->hasAstBeenChanged = true;
    }
}

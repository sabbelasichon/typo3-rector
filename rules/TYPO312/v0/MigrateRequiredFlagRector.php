<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO312\v0;

use PhpParser\Node\ArrayItem;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\String_;
use Ssch\TYPO3Rector\Helper\ArrayUtility;
use Ssch\TYPO3Rector\Helper\StringUtility;
use Ssch\TYPO3Rector\Rector\AbstractTcaRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Deprecation-97035-RequiredOptionInEvalKeyword.html
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Feature-97035-UtilizeRequiredDirectlyInTCAFieldConfiguration.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v0\tca\MigrateRequiredFlagRector\MigrateRequiredFlagRectorTest
 */
final class MigrateRequiredFlagRector extends AbstractTcaRector implements DocumentedRuleInterface
{
    /**
     * @var string
     */
    private const REQUIRED = 'required';

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate required flag', [new CodeSample(
            <<<'CODE_SAMPLE'
return [
    'columns' => [
        'required_column' => [
            'config' => [
                'eval' => 'trim,required',
            ],
        ],
    ],
];
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
return [
    'columns' => [
        'required_column' => [
            'config' => [
                'eval' => 'trim',
                'required' => true,
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

        $value = $this->valueResolver->getValue($evalArrayItem->value);
        if (! is_string($value)) {
            return;
        }

        if (! StringUtility::inList($value, self::REQUIRED)) {
            return;
        }

        $evalList = ArrayUtility::trimExplode(',', $value, true);

        // Remove "required" from $evalList
        $evalList = array_filter($evalList, static fn (string $eval) => $eval !== self::REQUIRED);

        if ($evalList !== []) {
            // Write back filtered 'eval'
            $evalArrayItem->value = new String_(implode(',', $evalList));
        } else {
            $this->removeArrayItemFromArrayByKey($configArray, 'eval');
        }

        // If required config exists already do not add one again
        $requiredItemToRemove = $this->extractArrayItemByKey($configArray, self::REQUIRED);
        if ($requiredItemToRemove instanceof ArrayItem) {
            $this->hasAstBeenChanged = true;
            return;
        }

        $configArray->items[] = new ArrayItem(new ConstFetch(new Name('true')), new String_(self::REQUIRED));

        $this->hasAstBeenChanged = true;
    }
}

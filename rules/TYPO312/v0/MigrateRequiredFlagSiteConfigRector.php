<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO312\v0;

use PhpParser\Node;
use PhpParser\Node\ArrayItem;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\String_;
use Ssch\TYPO3Rector\Helper\ArrayUtility;
use Ssch\TYPO3Rector\Helper\StringUtility;
use Ssch\TYPO3Rector\Rector\AbstractArrayDimFetchTcaRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Deprecation-97035-RequiredOptionInEvalKeyword.html
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Feature-97035-UtilizeRequiredDirectlyInTCAFieldConfiguration.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v0\MigrateRequiredFlagSiteConfigRector\MigrateRequiredFlagSiteConfigRectorTest
 */
final class MigrateRequiredFlagSiteConfigRector extends AbstractArrayDimFetchTcaRector implements DocumentedRuleInterface
{
    /**
     * @var string
     */
    private const REQUIRED = 'required';

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate required flag', [new CodeSample(
            <<<'CODE_SAMPLE'
$GLOBALS['SiteConfiguration']['site']['columns']['required_column1'] = [
    'required_column' => [
        'config' => [
            'eval' => 'trim,required',
        ],
    ],
];
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
$GLOBALS['SiteConfiguration']['site']['columns']['required_column1'] = [
    'required_column' => [
        'config' => [
            'eval' => 'trim',
            'required' = true,
        ],
    ],
];
CODE_SAMPLE
        )]);
    }

    /**
     * @param Assign $node
     */
    public function refactor(Node $node): ?Node
    {
        $columnName = $node->var;
        if (! $columnName instanceof ArrayDimFetch) {
            return null;
        }

        if (! $columnName->dim instanceof String_ && ! $columnName->dim instanceof Variable) {
            return null;
        }

        $rootLine = ['SiteConfiguration', 'site', 'columns'];
        $result = $this->isInRootLine($columnName, $rootLine);
        if (! $result) {
            return null;
        }

        $columnTca = $node->expr;

        $configArray = $this->extractSubArrayByKey($columnTca, self::CONFIG);
        if (! $configArray instanceof Array_) {
            return null;
        }

        if (! $this->hasKey($configArray, 'eval')) {
            return null;
        }

        $evalArrayItem = $this->extractArrayItemByKey($configArray, 'eval');
        if (! $evalArrayItem instanceof ArrayItem) {
            return null;
        }

        $value = $this->valueResolver->getValue($evalArrayItem->value);
        if (! is_string($value)) {
            return null;
        }

        if (! StringUtility::inList($value, self::REQUIRED)) {
            return null;
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
            return null;
        }

        $configArray->items[] = new ArrayItem(new ConstFetch(new Name('true')), new String_(self::REQUIRED));

        return $node;
    }
}

<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO314\v0;

use PhpParser\Node\ArrayItem;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use Ssch\TYPO3Rector\Rector\AbstractTcaRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/14.0/Breaking-107047-RemovePointerFieldFunctionalityOfTCAFlex.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateSingleDataStructureConfigurationRector\MigrateSingleDataStructureConfigurationRectorTest
 */
final class MigrateSingleDataStructureConfigurationRector extends AbstractTcaRector implements DocumentedRuleInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Remove pointer field functionality of TCA flex', [new CodeSample(
            <<<'CODE_SAMPLE'
return [
    'columns' => [
        'dColumn' => [
            'config' => [
                'type' => 'flex',
                'ds' => [
                    'default' => '<some>flex</some>',
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
        'dColumn' => [
            'config' => [
                'type' => 'flex',
                'ds' => '<some>flex</some>',
            ],
        ],
    ],
];
CODE_SAMPLE
        )]);
    }

    protected function refactorColumn(Expr $columnName, Expr $columnTca): void
    {
        $configArrayItem = $this->extractArrayItemByKey($columnTca, self::CONFIG);
        if (! $configArrayItem instanceof ArrayItem) {
            return;
        }

        $configArray = $configArrayItem->value;

        $typeArrayItem = $this->extractArrayItemByKey($configArray, self::TYPE);
        if (! $typeArrayItem instanceof ArrayItem) {
            return;
        }

        $type = $this->valueResolver->getValue($typeArrayItem->value);
        if ($type !== 'flex') {
            return;
        }

        $dsArrayItem = $this->extractArrayItemByKey($configArray, 'ds');
        if (! $dsArrayItem instanceof ArrayItem) {
            return;
        }

        if (! $dsArrayItem->value instanceof Array_) {
            return;
        }

        $dsArrayNode = $dsArrayItem->value;
        if (count($dsArrayNode->items) !== 1) {
            return;
        }

        $firstDsItem = $dsArrayNode->items[0];
        if (! $firstDsItem instanceof ArrayItem) {
            return;
        }

        $dsArrayItem->value = $firstDsItem->value;

        $this->hasAstBeenChanged = true;
    }
}

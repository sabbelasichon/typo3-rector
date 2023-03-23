<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v12\v0\tca;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use Ssch\TYPO3Rector\Helper\TcaHelperTrait;
use Ssch\TYPO3Rector\Rector\Tca\AbstractTcaRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.3/Feature-99739-AssociativeArrayKeysForTCAItems.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v0\tca\MigrateItemsIndexedKeysToAssociativeRector\MigrateItemsIndexedKeysToAssociativeRectorTest
 */
final class MigrateItemsIndexedKeysToAssociativeRector extends AbstractTcaRector
{
    use TcaHelperTrait;

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrates indexed item array keys to associative for type select, radio and check', [new CodeSample(
            <<<'CODE_SAMPLE'
    'columns' => [
        'aColumn' => [
            'config' => [
                'type' => 'select',
                'renderType' => 'selectCheckBox',
                'items' => [
                    ['My label', 0, 'my-icon', 'group1', 'My Description'],
                    ['My label 1', 1, 'my-icon', 'group1', 'My Description'],
                    ['My label 2', 2, 'my-icon', 'group1', 'My Description'],
                ],
            ],
        ],
    ],
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
'columns' => [
    'aColumn' => [
        'config' => [
            'type' => 'select',
            'renderType' => 'selectCheckBox',
            'items' => [
                ['label' => 'My label', 'value' => 0, 'icon' => 'my-icon', 'group' => 'group1', 'description' => 'My Description'],
                ['label' => 'My label 1', 'value' => 1, 'icon' => 'my-icon', 'group' => 'group1', 'description' => 'My Description'],
                ['label' => 'My label 2', 'value' => 2, 'icon' => 'my-icon', 'group' => 'group1', 'description' => 'My Description'],
            ]
        ],
    ],
],
CODE_SAMPLE
        )]);
    }

    protected function refactorColumn(Expr $columnName, Expr $columnTca): void
    {
        $configArray = $this->extractSubArrayByKey($columnTca, self::CONFIG);
        if (!$configArray instanceof Array_) {
            return;
        }

        if (
            !$this->isConfigType($configArray, 'select')
            && !$this->isConfigType($configArray, 'radio')
            && !$this->isConfigType($configArray, 'check')
        ) {
            return;
        }

        $arrayItemToChange = $this->extractArrayItemByKey($configArray, 'items');
        if (!$arrayItemToChange instanceof ArrayItem) {
            return;
        }

        // @todo Implement migration.

        $this->hasAstBeenChanged = true;
    }
}

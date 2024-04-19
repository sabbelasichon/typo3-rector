<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO312\v0;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use Ssch\TYPO3Rector\Rector\AbstractTcaRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Breaking-98479-RemovedFileReferenceRelatedFunctionality.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v0\RemoveObsoleteAppearanceConfigRector\RemoveObsoleteAppearanceConfigRectorTest
 */
final class RemoveObsoleteAppearanceConfigRector extends AbstractTcaRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Removes the obsolete appearance config options within TCA', [new CodeSample(
            <<<'CODE_SAMPLE'
return [
    'columns' => [
        'random' => [
            'config' => [
                'type' => 'group',
                'appearance' => [
                    'elementBrowserType' => 'db',
                    'elementBrowserAllowed' => 'foo',
                ],
            ],
        ],
        'random-inline' => [
            'config' => [
                'type' => 'inline',
                'appearance' => [
                    'headerThumbnail' => 'db',
                    'fileUploadAllowed' => 'foo',
                    'fileByUrlAllowed' => 'foo',
                ],
            ],
        ],
    ],
],
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
return [
    'columns' => [
        'random' => [
            'config' => [
                'type' => 'group',
            ],
        ],
        'random-inline' => [
            'config' => [
                'type' => 'inline',
            ],
        ],
    ],
],
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
        if (! $configArray instanceof Array_) {
            return;
        }

        if (! $this->isConfigType($configArray, 'group')
            && ! $this->isConfigType($configArray, 'inline')) {
            return;
        }

        if (! $this->hasKey($configArray, 'appearance')) {
            return;
        }

        if ($this->isConfigType($configArray, 'group')) {
            $this->removeArrayItemFromArrayByKey($configArray, 'appearance');
        }

        $appearanceArrayItem = $this->extractArrayItemByKey($configArray, 'appearance');
        if (! $appearanceArrayItem instanceof ArrayItem) {
            return;
        }

        $appearanceArray = $appearanceArrayItem->value;
        if (! $appearanceArray instanceof Array_) {
            return;
        }

        $this->removeArrayItemFromArrayByKey($appearanceArray, 'headerThumbnail');
        $this->removeArrayItemFromArrayByKey($appearanceArray, 'fileUploadAllowed');
        $this->removeArrayItemFromArrayByKey($appearanceArray, 'fileByUrlAllowed');

        if ($appearanceArray->items === []) {
            $this->removeArrayItemFromArrayByKey($configArray, 'appearance');
        }

        $this->hasAstBeenChanged = true;
    }
}

<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v7\v5;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use Ssch\TYPO3Rector\Rector\Tca\AbstractTcaRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/7.5/Deprecation-69736-SelectOptionIconsInOptionTagsRemoved.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v7\v5\RemoveIconsInOptionTagsRector\RemoveIconsInOptionTagsRectorTest
 */
final class RemoveIconsInOptionTagsRector extends AbstractTcaRector
{
    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Select option iconsInOptionTags removed', [new CodeSample(
            <<<'CODE_SAMPLE'
return [
    'columns' => [
        'foo' => [
            'label' => 'Label',
            'config' => [
                'type' => 'select',
                'maxitems' => 25,
                'autoSizeMax' => 10,
                'iconsInOptionTags' => 1,
            ],
        ],
    ],
];
CODE_SAMPLE
                ,
            <<<'CODE_SAMPLE'
return [
    'columns' => [
        'foo' => [
            'label' => 'Label',
            'config' => [
                'type' => 'select',
                'maxitems' => 25,
                'autoSizeMax' => 10,
            ],
        ],
    ],
];
CODE_SAMPLE
        )]);
    }

    protected function refactorColumn(Expr $columnName, Expr $columnTca): void
    {
        $config = $this->extractSubArrayByKey($columnTca, self::CONFIG);

        if (! $config instanceof Array_) {
            return;
        }

        $item = $this->extractArrayItemByKey($config, 'iconsInOptionTags');
        if (null !== $item) {
            $this->removeNode($item);
            $this->hasAstBeenChanged = true;
        }
    }
}

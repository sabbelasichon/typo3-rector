<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v11\v5;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use Ssch\TYPO3Rector\Helper\TcaHelperTrait;
use Ssch\TYPO3Rector\Rector\Tca\AbstractTcaRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/11.5/Important-95384-TCAInternal_typedbOptionalForTypegroup.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v11\v5\RemoveDefaultInternalTypeDBRector\RemoveDefaultInternalTypeDBRectorTest
 */
final class RemoveDefaultInternalTypeDBRector extends AbstractTcaRector
{
    use TcaHelperTrait;

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Remove the default type for internal_type', [new CodeSample(
            <<<'CODE_SAMPLE'
return [
    'columns' => [
        'foobar' => [
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
            ],
        ],
    ],
];
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
return [
    'columns' => [
        'foobar' => [
            'config' => [
                'type' => 'group',
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

        if (! $this->configIsOfInternalType($configArray, 'db')) {
            return;
        }

        $toRemoveArrayItem = $this->extractArrayItemByKey($configArray, 'internal_type');
        if ($toRemoveArrayItem instanceof ArrayItem) {
            $this->removeNode($toRemoveArrayItem);
            $this->hasAstBeenChanged = true;
        }
    }
}

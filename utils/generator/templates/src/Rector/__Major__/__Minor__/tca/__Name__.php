<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\__Major__\__Minor__\__Type__;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use Ssch\TYPO3Rector\Rector\Tca\AbstractTcaRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog __Changelog_Url__
 * @see \Ssch\TYPO3Rector\Tests\Rector\__Major__\__Minor__\__Type__\__Test_Directory__\__Name__Test
 */
final class __Name__ extends AbstractTcaRector
{
    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('__Description__', [new CodeSample(
            <<<'CODE_SAMPLE'
return [
    'columns' => [
    ],
];
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
return [
    'columns' => [
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

        // Your code here

        $this->hasAstBeenChanged = true;
    }
}

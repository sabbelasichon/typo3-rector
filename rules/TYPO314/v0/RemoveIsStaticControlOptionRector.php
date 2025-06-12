<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO314\v0;

use PhpParser\Node\Expr\Array_;
use Ssch\TYPO3Rector\Rector\AbstractTcaRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/14.0/Breaking-106863-TCAControlOptionIs_staticRemoved.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v14\v0\RemoveIsStaticControlOptionRector\RemoveIsStaticControlOptionRectorTest
 */
final class RemoveIsStaticControlOptionRector extends AbstractTcaRector implements DocumentedRuleInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Remove TCA control option is_static', [new CodeSample(
            <<<'CODE_SAMPLE'
return [
    'ctrl' => [
        'title' => 'foobar',
        'is_static' => 'foo',
    ],
    'columns' => [
    ],
];
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
return [
    'ctrl' => [
        'title' => 'foobar',
    ],
    'columns' => [
    ],
];
CODE_SAMPLE
        )]);
    }

    protected function refactorCtrl(Array_ $ctrlArray): void
    {
        $this->removeArrayItemFromArrayByKey($ctrlArray, 'is_static');
    }
}

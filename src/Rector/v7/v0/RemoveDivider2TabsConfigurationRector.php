<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v7\v0;

use PhpParser\Node\Expr\Array_;
use Ssch\TYPO3Rector\Rector\Tca\AbstractTcaRector;
use Ssch\TYPO3Rector\Rector\Tca\TcaHelperTrait;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/7.0/Breaking-62833-Dividers2Tabs.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v7\v0\RemoveDivider2TabsConfigurationRector\RemoveDivider2TabsConfigurationRectorTest
 */
final class RemoveDivider2TabsConfigurationRector extends AbstractTcaRector
{
    use TcaHelperTrait;

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Removed dividers2tabs functionality', [new CodeSample(
            <<<'CODE_SAMPLE'
return [
    'ctrl' => [
        'dividers2tabs' => true,
        'label' => 'complete_identifier',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
    ],
    'columns' => [
    ],
];
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
return [
    'ctrl' => [
        'label' => 'complete_identifier',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
    ],
    'columns' => [
    ],
];
CODE_SAMPLE
        )]);
    }

    protected function refactorCtrl(Array_ $ctrlArray): void
    {
        $this->hasAstBeenChanged = $this->removeArrayItemFromArrayByKey($ctrlArray, 'dividers2tabs');
    }
}

<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO3__Major__\__MinorPrefixed__;

use __Base_Rector_Class__;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog __Changelog_Url__
 * @see \Ssch\TYPO3Rector\Tests\Rector\__MajorPrefixed__\__MinorPrefixed__\__Test_Directory__\__Name__Test
 */
final class __Name__ extends __Base_Rector_ShortClassName__
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('__Description__', [new CodeSample(
            <<<'CODE_SAMPLE'
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
CODE_SAMPLE
        )]);
    }

__Base_Rector_Body_Template__
}

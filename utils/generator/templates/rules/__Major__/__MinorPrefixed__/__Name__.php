<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\__Major__\__MinorPrefixed__;

use __Base_Rector_Class__;
use Rector\RuleDoc\CodeSample\CodeSample;
use Rector\RuleDoc\Contract\DocumentedRuleInterface;
use Rector\RuleDoc\RuleDefinition;

/**
 * @changelog __Changelog_Url__
 * @see \Ssch\TYPO3Rector\Tests\Rector\__MajorPrefixed__\__MinorPrefixed__\__Test_Directory__\__Name__Test
 */
final class __Name__ extends __Base_Rector_ShortClassName__ implements DocumentedRuleInterface
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

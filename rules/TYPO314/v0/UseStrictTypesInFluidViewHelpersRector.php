<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO314\v0;

use PhpParser\Node;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/14.0/Breaking-108148-StrictTypesInFluidViewHelpers.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v14\v0\UseStrictTypesInFluidViewHelpersRector\UseStrictTypesInFluidViewHelpersRectorTest
 */
final class UseStrictTypesInFluidViewHelpersRector extends AbstractRector implements DocumentedRuleInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Use Strict Types in Fluid ViewHelpers', [new CodeSample(
            <<<'CODE_SAMPLE'
class MyViewHelper extends AbstractViewHelper
{
    public function initialize()
    {
    }

    public function initializeArguments()
    {
    }
}
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
class MyViewHelper extends AbstractViewHelper
{
    public function initialize(): void
    {
    }

    public function initializeArguments(): void
    {
    }
}
CODE_SAMPLE
        )]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [];
    }

    public function refactor(Node $node): ?Node
    {
        return null;
    }
}

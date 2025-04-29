<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO311\v5;

use PhpParser\Node;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/11.5/Deprecation-95222-ExtbaseViewInterface.html
 */
final class RemoveTypeHintViewInterfaceRector extends AbstractRector implements DocumentedRuleInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('RemoveTypeHintViewInterfaceRector is deprecated.', [new CodeSample(
            <<<'CODE_SAMPLE'
Do not use this rule any more. Please use MigrateExtbaseViewInterfaceRector instead.
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
Do not use this rule any more. Please use MigrateExtbaseViewInterfaceRector instead.
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

    /**
     * @deprecated in favor of MigrateExtbaseViewInterfaceRector
     */
    public function refactor(Node $node): ?Node
    {
        return null;
    }
}

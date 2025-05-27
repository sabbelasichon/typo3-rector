<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO313\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Scalar\String_;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/13.0/Breaking-102627-RemovedSpecialPropertiesOfPageArraysInPageRepository.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v13\v0\RemoveSpecialPropertiesOfPageArraysRector\RemoveSpecialPropertiesOfPageArraysRectorTest
 */
final class RemoveSpecialPropertiesOfPageArraysRector extends AbstractRector implements DocumentedRuleInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Remove', [new CodeSample(
            <<<'CODE_SAMPLE'
$rows['_PAGES_OVERLAY_UID']
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
$rows['_LOCALIZED_UID']
CODE_SAMPLE
        ),
            new CodeSample(
                <<<'CODE_SAMPLE'
$rows['_PAGES_OVERLAY_REQUESTEDLANGUAGE']
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
$rows['_REQUESTED_OVERLAY_LANGUAGE']
CODE_SAMPLE
            )]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [ArrayDimFetch::class];
    }

    /**
     * @param ArrayDimFetch $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($this->shouldSkip($node)) {
            return null;
        }

        /** @var String_ $string */
        $string = $node->dim;
        if ($string->value === '_PAGES_OVERLAY_UID') {
            $node->dim = new String_('_LOCALIZED_UID');
        }

        if ($string->value === '_PAGES_OVERLAY_REQUESTEDLANGUAGE') {
            $node->dim = new String_('_REQUESTED_OVERLAY_LANGUAGE');
        }

        return $node;
    }

    private function shouldSkip(ArrayDimFetch $node): bool
    {
        if (! $node->dim instanceof String_) {
            return true;
        }

        return $node->dim->value !== '_PAGES_OVERLAY_UID' && $node->dim->value !== '_PAGES_OVERLAY_REQUESTEDLANGUAGE';
    }
}

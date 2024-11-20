<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO313\v4;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Expression;
use PhpParser\NodeTraverser;
use Ssch\TYPO3Rector\Rector\AbstractArrayDimFetchTcaRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/13.4/Deprecation-105076-PluginContentElementAndPluginSubTypes.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v13\v4\RemoveTcaSubTypesExcludeListTCARector\RemoveTcaSubTypesExcludeListTCARectorTest
 */
final class RemoveTcaSubTypesExcludeListTCARector extends AbstractArrayDimFetchTcaRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Remove subtypes_excludelist from list type', [new CodeSample(
            <<<'CODE_SAMPLE'
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['my_plugin'] = 'layout,select_key,pages';
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
-
CODE_SAMPLE
        )]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Expression::class];
    }

    /**
     * @param Expression $node
     */
    public function refactor(Node $node): ?int
    {
        $assignment = $node->expr;
        if (! $assignment instanceof Assign) {
            return null;
        }

        $pluginSignature = $assignment->var;
        if (! $pluginSignature instanceof ArrayDimFetch) {
            return null;
        }

        if (! $pluginSignature->dim instanceof String_ && ! $pluginSignature->dim instanceof Variable) {
            return null;
        }

        $rootLine = ['TCA', 'tt_content', 'types', 'list', 'subtypes_excludelist'];
        $result = $this->isInRootLine($pluginSignature, $rootLine);
        if (! $result) {
            return null;
        }

        return NodeTraverser::REMOVE_NODE;
    }
}

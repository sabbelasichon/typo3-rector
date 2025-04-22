<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO313\v2;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Expression;
use PhpParser\NodeVisitor;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/13.2/Deprecation-103752-ObsoleteGLOBALSTYPO3_CONF_VARSFEaddRootLineFields.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v13\v2\RemoveAddRootLinefieldsRector\RemoveAddRootLinefieldsRectorTest
 */
final class RemoveAddRootLineFieldsRector extends AbstractRector implements DocumentedRuleInterface
{
    private ValueResolver $valueResolver;

    public function __construct(ValueResolver $valueResolver)
    {
        $this->valueResolver = $valueResolver;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Remove obsolete $GLOBALS[\'TYPO3_CONF_VARS\'][\'FE\'][\'addRootLineFields\']', [
            new CodeSample(
                <<<'CODE_SAMPLE'
$GLOBALS['TYPO3_CONF_VARS']['FE']['addRootLineFields'] = 'foo';
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
-
CODE_SAMPLE
            ),
        ]);
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

        $addRootLineFields = $assignment->var;
        if (! $addRootLineFields instanceof ArrayDimFetch) {
            return null;
        }

        $rootLine = ['TYPO3_CONF_VARS', 'FE', 'addRootLineFields'];
        $result = $this->isInRootLine($assignment, $rootLine);
        if (! $result) {
            return null;
        }

        return NodeVisitor::REMOVE_NODE;
    }

    /**
     * @param string[] $rootLine
     */
    private function isInRootLine(Assign $arrayDimFetch, array &$rootLine): bool
    {
        $isInRootLine = false;
        $this->traverseNodesWithCallable($arrayDimFetch->var, function (Node $node) use (
            &$isInRootLine,
            &$rootLine
        ): ?ArrayDimFetch {
            if (! $node instanceof ArrayDimFetch) {
                return null;
            }

            if (! $node->dim instanceof String_) {
                return null;
            }

            $lastArrayElement = end($rootLine);
            if ($this->valueResolver->isValue($node->dim, $lastArrayElement)) {
                array_pop($rootLine);
                $isInRootLine = true;
            } else {
                $isInRootLine = false;
            }

            return $node;
        });
        return $isInRootLine;
    }
}

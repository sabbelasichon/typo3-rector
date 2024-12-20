<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Scalar\String_;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\Rector\AbstractRector;
use Ssch\TYPO3Rector\Helper\TcaHelperTrait;

/**
 * Base rector that detects Assignments containing TCA definitions and allows to refactor them
 */
abstract class AbstractArrayDimFetchTcaRector extends AbstractRector
{
    use TcaHelperTrait;

    protected const CONFIG = 'config';

    protected bool $hasAstBeenChanged = false;

    protected ValueResolver $valueResolver;

    public function __construct(ValueResolver $valueResolver)
    {
        $this->valueResolver = $valueResolver;
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Assign::class];
    }

    /**
     * @param string[] $rootLine
     */
    protected function isInRootLine(ArrayDimFetch $arrayDimFetch, array &$rootLine): bool
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

    protected function findParent(ArrayDimFetch $arrayDimFetch, string $key): ?ArrayDimFetch
    {
        $foundNode = null;
        $this->traverseNodesWithCallable($arrayDimFetch->var, function (Node $node) use (
            &$foundNode,
            $key
        ): ?ArrayDimFetch {
            if (! $node instanceof ArrayDimFetch) {
                return null;
            }

            if (! $node->dim instanceof String_) {
                return null;
            }

            if (! $this->valueResolver->isValue($node->dim, $key)) {
                return null;
            }

            $foundNode = $node;
            return $node;
        });
        return $foundNode;
    }
}

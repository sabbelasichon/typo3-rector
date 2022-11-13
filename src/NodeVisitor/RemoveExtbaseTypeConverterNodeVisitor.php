<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\NodeVisitor;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor;
use Rector\NodeNameResolver\NodeNameResolver;

final class RemoveExtbaseTypeConverterNodeVisitor implements NodeVisitor
{
    private NodeNameResolver $nodeNameResolver;

    public function __construct(NodeNameResolver $nodeNameResolver)
    {
        $this->nodeNameResolver = $nodeNameResolver;
    }

    public function beforeTraverse(array $nodes): ?array
    {
        return $nodes;
    }

    public function enterNode(Node $node): Node
    {
        return $node;
    }

    public function leaveNode(Node $node)
    {
        if (! $node instanceof ClassMethod) {
            return $node;
        }

        if (! $this->nodeNameResolver->isNames(
            $node->name,
            ['getSupportedSourceTypes', 'getSupportedTargetType', 'getPriority', 'canConvertFrom']
        )) {
            return $node;
        }

        return NodeTraverser::REMOVE_NODE;
    }

    public function afterTraverse(array $nodes): ?array
    {
        return $nodes;
    }
}

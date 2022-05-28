<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\ComposerPackages\NodeDecorator;

use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Stmt;
use Rector\NodeNameResolver\NodeNameResolver;
use Symplify\Astral\NodeTraverser\SimpleCallableNodeTraverser;

final class RemovePackageVersionsDecorator
{
    public function __construct(
        private readonly NodeNameResolver $nodeNameResolver,
        private readonly SimpleCallableNodeTraverser $simpleCallableNodeTraverser
    ) {
    }

    /**
     * @param Stmt[] $nodes
     */
    public function refactor(array $nodes): void
    {
        $this->simpleCallableNodeTraverser->traverseNodesWithCallable($nodes, function (Node $node) {
            if (! $node instanceof Assign) {
                return null;
            }

            if (! $this->nodeNameResolver->isName($node->var, 'composerExtensions')) {
                return null;
            }

            if (! $node->expr instanceof Array_) {
                return null;
            }

            // remove all items
            $array = $node->expr;
            $array->items = [];

            return $node;
        });
    }
}

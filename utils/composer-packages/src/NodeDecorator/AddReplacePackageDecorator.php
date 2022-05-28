<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\ComposerPackages\NodeDecorator;

use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Name\FullyQualified;
use Rector\Composer\ValueObject\RenamePackage;
use Rector\Core\PhpParser\Node\NodeFactory;
use Rector\NodeNameResolver\NodeNameResolver;
use Symplify\Astral\NodeTraverser\SimpleCallableNodeTraverser;

/**
 * @see \Ssch\TYPO3Rector\ComposerPackages\Tests\Rector\AddReplacePackageRector\AddReplacePackageRectorTest
 */
final class AddReplacePackageDecorator
{
    public function __construct(
        private readonly NodeNameResolver $nodeNameResolver,
        private readonly NodeFactory $nodeFactory,
        private readonly SimpleCallableNodeTraverser $simpleCallableNodeTraverser
    ) {
    }

    /**
     * @param Node\Stmt[] $nodes
     * @param RenamePackage[] $renamePackages
     */
    public function refactor(array $nodes, array $renamePackages): void
    {
        $this->simpleCallableNodeTraverser->traverseNodesWithCallable($nodes, function (Node $node) use (
            $renamePackages
        ) {
            if (! $node instanceof Assign) {
                return null;
            }

            if (! $this->nodeNameResolver->isName($node->var, 'composerExtensions')) {
                return;
            }

            if (! $node->expr instanceof Array_) {
                return;
            }

            $array = $node->expr;

            foreach ($renamePackages as $renamePackage) {
                $array->items[] = new ArrayItem($this->createRenamePackageNew($renamePackage));
            }
        });
    }

    private function createRenamePackageNew(RenamePackage $renamePackage): New_
    {
        $args = $this->nodeFactory->createArgs([
            $renamePackage->getOldPackageName(),
            $renamePackage->getNewPackageName(),
        ]);

        return new New_(new FullyQualified('Rector\Composer\ValueObject\RenamePackage'), $args);
    }
}

<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\ComposerPackages\NodeDecorator;

use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt;
use Rector\Core\PhpParser\Node\NodeFactory;
use Rector\NodeNameResolver\NodeNameResolver;
use Ssch\TYPO3Rector\ComposerPackages\ValueObject\ExtensionVersion;
use Symplify\Astral\NodeTraverser\SimpleCallableNodeTraverser;

final class AddPackageVersionDecorator
{
    public function __construct(
        private readonly SimpleCallableNodeTraverser $simpleCallableNodeTraverser,
        private readonly NodeNameResolver $nodeNameResolver,
        private readonly NodeFactory $nodeFactory,
    ) {
    }

    /**
     * @param Stmt[] $stmts
     */
    public function refactor(array $stmts, ExtensionVersion $extensionVersion): void
    {
        $this->simpleCallableNodeTraverser->traverseNodesWithCallable($stmts, function (Node $node) use (
            $extensionVersion
        ) {
            if (! $node instanceof Assign) {
                return null;
            }

            if (! $this->nodeNameResolver->isName($node->var, 'composerExtensions')) {
                return null;
            }

            if (! $node->expr instanceof Array_) {
                return null;
            }

            $array = $node->expr;
            $array->items[] = new ArrayItem($this->createNewPackageVersion($extensionVersion));

            return $node;
        });
    }

    private function createNewPackageVersion(ExtensionVersion $extensionVersion): New_
    {
        $args = $this->nodeFactory->createArgs([
            $extensionVersion->packageName(),
            sprintf('^%s', ltrim($extensionVersion->version(), 'v')),
        ]);

        return new New_(new FullyQualified('Rector\Composer\ValueObject\PackageAndVersion'), $args);
    }
}

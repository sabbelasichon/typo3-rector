<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\ComposerPackages\NodeDecorator;

use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Expression;
use Rector\Core\PhpParser\Node\NodeFactory;
use Rector\NodeNameResolver\NodeNameResolver;
use Ssch\TYPO3Rector\ComposerPackages\NodeAnalyzer\SymfonyPhpConfigClosureAnalyzer;
use Ssch\TYPO3Rector\ComposerPackages\ValueObject\ExtensionVersion;
use Symplify\Astral\NodeTraverser\SimpleCallableNodeTraverser;

/**
 * @see \Ssch\TYPO3Rector\ComposerPackages\Tests\Rector\AddPackageVersionRector\AddPackageVersionRectorTest
 */
final class AddPackageVersionClosureDecorator
{
    public function __construct(
        private readonly SymfonyPhpConfigClosureAnalyzer $symfonyPhpConfigClosureAnalyzer,
        private readonly SimpleCallableNodeTraverser $simpleCallableNodeTraverser,
        private readonly NodeNameResolver $nodeNameResolver,
        private readonly NodeFactory $nodeFactory,
    ) {
    }

    /**
     * @param Node[] $nodes
     */
    public function refactor(array $nodes, ExtensionVersion $extensionVersion): void
    {
        $this->simpleCallableNodeTraverser->traverseNodesWithCallable($nodes, function (Node $node) use ($extensionVersion) {
            if (! $node instanceof Closure) {
                return null;
            }

            if (! $this->symfonyPhpConfigClosureAnalyzer->isPhpConfigClosure($node)) {
                return null;
            }

            /** @var Closure $closure */
            $closure = $node;

            /** @var Expression $stmt */
            foreach ($closure->stmts as $stmt) {
                if (! $stmt->expr instanceof Assign) {
                    continue;
                }

                $assign = $stmt->expr;
                if (! $this->nodeNameResolver->isName($assign->var, 'composerExtensions')) {
                    continue;
                }

                if (! $assign->expr instanceof Array_) {
                    continue;
                }

                $array = $assign->expr;
                $array->items[] = new ArrayItem(
                    $this->createNewPackageVersion($extensionVersion)
                );
            }

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

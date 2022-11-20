<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\NodeAnalyzer;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Type\ObjectType;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\NodeTypeResolver;

final class ExtbaseControllerRedirectAnalyzer
{
    private NodeTypeResolver $nodeTypeResolver;

    private BetterNodeFinder $betterNodeFinder;

    private NodeNameResolver $nodeNameResolver;

    public function __construct(
        NodeTypeResolver $nodeTypeResolver,
        BetterNodeFinder $betterNodeFinder,
        NodeNameResolver $nodeNameResolver
    ) {
        $this->nodeTypeResolver = $nodeTypeResolver;
        $this->betterNodeFinder = $betterNodeFinder;
        $this->nodeNameResolver = $nodeNameResolver;
    }

    /**
     * @param string[] $redirectMethods
     */
    public function hasRedirectCall(ClassMethod $classMethod, array $redirectMethods): bool
    {
        return (bool) $this->betterNodeFinder->find((array) $classMethod->stmts, function (Node $node) use (
            $redirectMethods
        ): bool {
            if (! $node instanceof MethodCall) {
                return false;
            }

            if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
                $node,
                new ObjectType('TYPO3\CMS\Extbase\Mvc\Controller\ActionController')
            )) {
                return false;
            }

            return $this->nodeNameResolver->isNames($node->name, $redirectMethods);
        });
    }
}

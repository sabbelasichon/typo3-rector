<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\NodeFactory;

use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PHPStan\Analyser\Scope;
use PHPStan\Type\ObjectType;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\NodeTypeResolver;
use Rector\PhpParser\Node\NodeFactory;
use Rector\PhpParser\Node\Value\ValueResolver;

final class GeneralUtilitySuperGlobalsToPsr7ServerRequestFactory
{
    /**
     * @readonly
     */
    private NodeFactory $nodeFactory;

    /**
     * @readonly
     */
    private Typo3RequestNodeFactory $typo3RequestNodeFactory;

    /**
     * @readonly
     */
    private NodeTypeResolver $nodeTypeResolver;

    /**
     * @readonly
     */
    private NodeNameResolver $nodeNameResolver;

    /**
     * @readonly
     */
    private ValueResolver $valueResolver;

    public function __construct(
        Typo3RequestNodeFactory $typo3RequestNodeFactory,
        NodeFactory $nodeFactory,
        NodeTypeResolver $nodeTypeResolver,
        NodeNameResolver $nodeNameResolver,
        ValueResolver $valueResolver
    ) {
        $this->typo3RequestNodeFactory = $typo3RequestNodeFactory;
        $this->nodeFactory = $nodeFactory;
        $this->nodeTypeResolver = $nodeTypeResolver;
        $this->nodeNameResolver = $nodeNameResolver;
        $this->valueResolver = $valueResolver;
    }

    /**
     * @return ArrayDimFetch|MethodCall|null
     */
    public function refactorToPsr7MethodCall(
        Scope $scope,
        StaticCall $node,
        string $psr7ServerRequestMethodName,
        string $oldSuperGlobalsMethodName
    ) {
        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $node,
            new ObjectType('TYPO3\CMS\Core\Utility\GeneralUtility')
        )) {
            return null;
        }

        if (! $this->nodeNameResolver->isName($node->name, $oldSuperGlobalsMethodName)) {
            return null;
        }

        $requestFetcherVariable = $this->typo3RequestNodeFactory->getServerRequestInScope($scope);

        if (! isset($node->getArgs()[0])) {
            return $this->nodeFactory->createMethodCall($requestFetcherVariable, $psr7ServerRequestMethodName);
        }

        if ($this->valueResolver->isNull($node->getArgs()[0]->value)) {
            return $this->nodeFactory->createMethodCall($requestFetcherVariable, $psr7ServerRequestMethodName);
        }

        return new ArrayDimFetch(
            $this->nodeFactory->createMethodCall($requestFetcherVariable, $psr7ServerRequestMethodName),
            $node->getArgs()[0]
                ->value
        );
    }
}

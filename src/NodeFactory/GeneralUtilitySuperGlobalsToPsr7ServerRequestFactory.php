<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\NodeFactory;

use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PHPStan\Reflection\ClassReflection;
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
    private Typo3GlobalsFactory $typo3GlobalsFactory;

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
        NodeFactory $nodeFactory,
        Typo3GlobalsFactory $typo3GlobalsFactory,
        NodeTypeResolver $nodeTypeResolver,
        NodeNameResolver $nodeNameResolver,
        ValueResolver $valueResolver
    ) {
        $this->nodeFactory = $nodeFactory;
        $this->typo3GlobalsFactory = $typo3GlobalsFactory;
        $this->nodeTypeResolver = $nodeTypeResolver;
        $this->nodeNameResolver = $nodeNameResolver;
        $this->valueResolver = $valueResolver;
    }

    /**
     * @return ArrayDimFetch|MethodCall|null
     */
    public function refactorToPsr7MethodCall(
        ?ClassReflection $classReflection,
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

        if ($classReflection instanceof ClassReflection
            && $classReflection->isSubclassOf('TYPO3\CMS\Extbase\Mvc\Controller\ActionController')
        ) {
            $requestFetcherVariable = $this->nodeFactory->createPropertyFetch('this', 'request');
        } else {
            $requestFetcherVariable = $this->typo3GlobalsFactory->create('TYPO3_REQUEST');
        }

        if (! isset($node->args[0])) {
            return $this->nodeFactory->createMethodCall($requestFetcherVariable, $psr7ServerRequestMethodName);
        }

        if ($this->valueResolver->isNull($node->args[0]->value)) {
            return $this->nodeFactory->createMethodCall($requestFetcherVariable, $psr7ServerRequestMethodName);
        }

        return new ArrayDimFetch(
            $this->nodeFactory->createMethodCall($requestFetcherVariable, $psr7ServerRequestMethodName),
            $node->args[0]->value
        );
    }
}

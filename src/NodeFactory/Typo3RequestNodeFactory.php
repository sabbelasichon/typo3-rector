<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\NodeFactory;

use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\Variable;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use Rector\PhpParser\Node\NodeFactory;

final class Typo3RequestNodeFactory
{
    /**
     * @readonly
     */
    private NodeFactory $nodeFactory;

    /**
     * @readonly
     */
    private Typo3GlobalsFactory $typo3GlobalsFactory;

    public function __construct(NodeFactory $nodeFactory, Typo3GlobalsFactory $typo3GlobalsFactory)
    {
        $this->nodeFactory = $nodeFactory;
        $this->typo3GlobalsFactory = $typo3GlobalsFactory;
    }

    /**
     * If a `$request` variable is in scope, returns it.
     * In an ActionController context, returns `$this->request`.
     * Otherwise, returns `$GLOBALS['TYPO3_REQUEST']`.
     *
     * @return ArrayDimFetch|PropertyFetch|Variable
     */
    public function getServerRequestInScope(Scope $scope)
    {
        if ($scope->hasVariableType('request')->yes() && $scope->getVariableType('request')->isObject()->yes()) {
            return new Variable('request');
        }

        $classReflection = $scope->getClassReflection();
        if ($classReflection instanceof ClassReflection
            && $classReflection->is('TYPO3\CMS\Extbase\Mvc\Controller\ActionController')
        ) {
            return $this->nodeFactory->createPropertyFetch('this', 'request');
        }

        return $this->typo3GlobalsFactory->create('TYPO3_REQUEST');
    }
}

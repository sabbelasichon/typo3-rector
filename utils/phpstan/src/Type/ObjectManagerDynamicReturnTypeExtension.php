<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\PHPStan\Type;

use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\DynamicMethodReturnTypeExtension;
use PHPStan\Type\Type;
use Ssch\TYPO3Rector\PHPStan\TypeResolver\ArgumentTypeResolver;

final class ObjectManagerDynamicReturnTypeExtension implements DynamicMethodReturnTypeExtension
{
    /**
     * @readonly
     */
    private ArgumentTypeResolver $argumentTypeResolver;

    public function __construct(ArgumentTypeResolver $argumentTypeResolver)
    {
        $this->argumentTypeResolver = $argumentTypeResolver;
    }

    public function getClass(): string
    {
        return 'TYPO3\CMS\Extbase\Object\ObjectManagerInterface';
    }

    public function isMethodSupported(MethodReflection $methodReflection): bool
    {
        return $methodReflection->getName() === 'get';
    }

    public function getTypeFromMethodCall(
        MethodReflection $methodReflection,
        MethodCall $methodCall,
        Scope $scope
    ): Type {
        return $this->argumentTypeResolver->resolveFromMethodCall($methodCall, $methodReflection);
    }
}

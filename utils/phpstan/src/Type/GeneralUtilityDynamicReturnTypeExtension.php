<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\PHPStan\Type;

use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\StaticCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\ParametersAcceptorSelector;
use PHPStan\Type\DynamicStaticMethodReturnTypeExtension;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;

final class GeneralUtilityDynamicReturnTypeExtension implements DynamicStaticMethodReturnTypeExtension
{
    public function getClass(): string
    {
        return 'TYPO3\CMS\Core\Utility\GeneralUtility';
    }

    public function isStaticMethodSupported(MethodReflection $methodReflection): bool
    {
        return 'makeInstance' === $methodReflection->getName();
    }

    public function getTypeFromStaticMethodCall(
        MethodReflection $methodReflection,
        StaticCall $methodCall,
        Scope $scope
    ): Type {
        $arg = $methodCall->args[0]->value;
        if (! ($arg instanceof ClassConstFetch)) {
            return ParametersAcceptorSelector::selectSingle($methodReflection->getVariants())->getReturnType();
        }
        $class = $arg->class;

        return new ObjectType((string) $class);
    }
}

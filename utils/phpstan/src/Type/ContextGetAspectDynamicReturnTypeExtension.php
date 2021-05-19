<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\PHPStan\Type;

use PhpParser\Node\Arg;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Scalar\String_;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\DynamicMethodReturnTypeExtension;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use TYPO3\CMS\Core\Context\Context;

final class ContextGetAspectDynamicReturnTypeExtension implements DynamicMethodReturnTypeExtension
{
    public function getClass(): string
    {
        return Context::class;
    }

    public function isMethodSupported(MethodReflection $methodReflection): bool
    {
        return 'getAspect' === $methodReflection->getName();
    }

    public function getTypeFromMethodCall(
        MethodReflection $methodReflection,
        MethodCall $methodCall,
        Scope $scope
    ): Type {
        $defaultObjectType = new ObjectType('TYPO3\CMS\Core\Context\AspectInterface');

        if (! ($argument = $methodCall->args[0] ?? null) instanceof Arg) {
            return $defaultObjectType;
        }
        /** @var Arg $argument */

        if (! ($string = $argument->value ?? null) instanceof String_) {
            return $defaultObjectType;
        }
        /** @var String_ $string */

        switch ($string->value) {
            case 'date':
                $type = new ObjectType('TYPO3\CMS\Core\Context\DateTimeAspect');
                break;
            case 'visibility':
                $type = new ObjectType('TYPO3\CMS\Core\Context\VisibilityAspect');
                break;
            case 'frontend.user':
            case 'backend.user':
                $type = new ObjectType('TYPO3\CMS\Core\Context\UserAspect');
                break;
            case 'workspace':
                $type = new ObjectType('TYPO3\CMS\Core\Context\WorkspaceAspect');
                break;
            case 'language':
                $type = new ObjectType('TYPO3\CMS\Core\Context\LanguageAspect');
                break;
            case 'typoscript':
                $type = new ObjectType('TYPO3\CMS\Core\Context\TypoScriptAspect');
                break;
            default:
                $type = $defaultObjectType;
                break;
        }

        return $type;
    }
}

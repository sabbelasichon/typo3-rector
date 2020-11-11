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
use TYPO3\CMS\Core\Context\AspectInterface;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Context\DateTimeAspect;
use TYPO3\CMS\Core\Context\LanguageAspect;
use TYPO3\CMS\Core\Context\TypoScriptAspect;
use TYPO3\CMS\Core\Context\UserAspect;
use TYPO3\CMS\Core\Context\VisibilityAspect;
use TYPO3\CMS\Core\Context\WorkspaceAspect;

final class ContextGetAspectDynamicReturnTypeExtension implements DynamicMethodReturnTypeExtension
{
    public function getClass(): string
    {
        return Context::class;
    }

    public function isMethodSupported(MethodReflection $methodReflection): bool
    {
        return $methodReflection->getName() === 'getAspect';
    }

    public function getTypeFromMethodCall(MethodReflection $methodReflection, MethodCall $methodCall, Scope $scope): Type
    {
        $defaultObjectType = new ObjectType(AspectInterface::class);

        if (!($argument = $methodCall->args[0] ?? null) instanceof Arg) {
            return $defaultObjectType;
        }
        /** @var Arg $argument */

        if (!($string = $argument->value ?? null) instanceof String_) {
            return $defaultObjectType;
        }
        /** @var String_ $string */

        switch ($string->value) {
            case 'date':
                $type = new ObjectType(DateTimeAspect::class);
                break;
            case 'visibility':
                $type = new ObjectType(VisibilityAspect::class);
                break;
            case 'frontend.user':
            case 'backend.user':
                $type = new ObjectType(UserAspect::class);
                break;
            case 'workspace':
                $type = new ObjectType(WorkspaceAspect::class);
                break;
            case 'language':
                $type = new ObjectType(LanguageAspect::class);
                break;
            case 'typoscript':
                $type = new ObjectType(TypoScriptAspect::class);
                break;
            default:
                $type = $defaultObjectType;
                break;
        }

        return $type;
    }
}

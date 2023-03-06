<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\AttributeDecorator;

use PhpParser\Node\Arg;
use PhpParser\Node\Attribute;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Scalar\String_;
use Rector\Core\PhpParser\Node\Value\ValueResolver;
use Rector\Php55\Rector\String_\StringClassNameToClassConstantRector;
use Rector\Php80\Contract\AttributeDecoratorInterface;

final class ExtbaseValidateAttributeDecorator implements AttributeDecoratorInterface
{
    private StringClassNameToClassConstantRector $stringClassNameToClassConstantRector;

    private ValueResolver $valueResolver;

    public function __construct(
        ValueResolver $valueResolver,
        StringClassNameToClassConstantRector $stringClassNameToClassConstantRector
    ) {
        $this->stringClassNameToClassConstantRector = $stringClassNameToClassConstantRector;
        $this->valueResolver = $valueResolver;
    }

    public function getAttributeName(): string
    {
        return 'TYPO3\\CMS\\Extbase\\Annotation\\Validate';
    }

    public function decorate(Attribute $attribute): void
    {
        $newArguments = new Array_();

        foreach ($attribute->args as $arg) {
            if (null === $arg->name) {
                $key = new String_('validator');
            } else {
                $key = new String_($arg->name->toString());
            }

            if ($this->valueResolver->isValue($key, 'validator')) {
                $className = ltrim($this->valueResolver->getValue($arg->value), '\\');
                $classConstant = $this->stringClassNameToClassConstantRector->refactor(new String_($className));
                if ($classConstant instanceof ClassConstFetch) {
                    $value = $classConstant;
                } else {
                    $value = $arg->value;
                }
            } else {
                $value = $arg->value;
            }

            $newArguments->items[] = new ArrayItem($value, $key);
        }

        $attribute->args = [new Arg($newArguments)];
    }
}

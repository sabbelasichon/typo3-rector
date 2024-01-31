<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO312\AnnotationToAttribute;

use PhpParser\Node\Arg;
use PhpParser\Node\Attribute;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Identifier;
use PhpParser\Node\Scalar\String_;
use Rector\Php55\Rector\String_\StringClassNameToClassConstantRector;
use Rector\PhpParser\Node\Value\ValueResolver;
use Ssch\TYPO3Rector\TYPO312\Contract\AttributeDecoratorInterface;

final class ValidateAttributeDecorator implements AttributeDecoratorInterface
{
    /**
     * @readonly
     */
    private ValueResolver $valueResolver;

    /**
     * @readonly
     */
    private StringClassNameToClassConstantRector $stringClassNameToClassConstantRector;

    public function __construct(ValueResolver $valueResolver, StringClassNameToClassConstantRector $stringClassNameToClassConstantRector)
    {
        $this->valueResolver = $valueResolver;
        $this->stringClassNameToClassConstantRector = $stringClassNameToClassConstantRector;
    }

    public function supports(string $phpAttributeName): bool
    {
        return in_array($phpAttributeName, ['TYPO3\CMS\Extbase\Annotation\Validate', 'Extbase\Validate'], true);
    }

    public function decorate(Attribute $attribute): void
    {
        $newArguments = new Array_();

        foreach ($attribute->args as $arg) {
            $key = $arg->name instanceof Identifier ? new String_($arg->name->toString()) : new String_('validator');

            if ($this->valueResolver->isValue($key, 'validator')) {
                $className = ltrim($this->valueResolver->getValue($arg->value), '\\');
                $classConstant = $this->stringClassNameToClassConstantRector->refactor(new String_($className));
                $value = $classConstant instanceof ClassConstFetch ? $classConstant : $arg->value;
            } else {
                $value = $arg->value;
            }

            $newArguments->items[] = new ArrayItem($value, $key);
        }

        $attribute->args = [new Arg($newArguments)];
    }
}

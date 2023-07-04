<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\AttributeDecorator;

use PhpParser\Node\Arg;
use PhpParser\Node\Attribute;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Identifier;
use PhpParser\Node\Scalar\String_;
use Rector\Php80\Contract\AttributeDecoratorInterface;

final class ExtbaseIgnoreValidationAttributeDecorator implements AttributeDecoratorInterface
{
    public function getAttributeName(): string
    {
        return 'TYPO3\\CMS\\Extbase\\Annotation\\IgnoreValidation';
    }

    public function decorate(Attribute $attribute): void
    {
        $newArguments = new Array_();

        foreach ($attribute->args as $arg) {
            $key = $arg->name instanceof Identifier ? new String_($arg->name->toString()) : new String_('value');

            $newArguments->items[] = new ArrayItem($arg->value, $key);
        }

        $attribute->args = [new Arg($newArguments)];
    }
}

<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO312\AnnotationToAttribute;

use PhpParser\Node\Arg;
use PhpParser\Node\ArrayItem;
use PhpParser\Node\Attribute;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Identifier;
use PhpParser\Node\Scalar\String_;
use Ssch\TYPO3Rector\TYPO312\Contract\AttributeDecoratorInterface;

final class IgnoreValidationAttributeDecorator implements AttributeDecoratorInterface
{
    public function supports(string $phpAttributeName): bool
    {
        return in_array(
            $phpAttributeName,
            ['TYPO3\CMS\Extbase\Annotation\IgnoreValidation', 'Extbase\IgnoreValidation'],
            true
        );
    }

    public function decorate(Attribute $attribute): void
    {
        $newArguments = new Array_();

        foreach ($attribute->args as $arg) {
            $key = $arg->name instanceof Identifier ? new String_($arg->name->toString()) : new String_('argumentName');

            $newArguments->items[] = new ArrayItem($arg->value, $key);
        }

        $attribute->args = [new Arg($newArguments)];
    }
}

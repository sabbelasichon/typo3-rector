<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO312\AnnotationToAttribute;

use PhpParser\Node\Arg;
use PhpParser\Node\ArrayItem;
use PhpParser\Node\Attribute;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Scalar\String_;
use Ssch\TYPO3Rector\TYPO312\Contract\AttributeDecoratorInterface;

final class CascadeAttributeDecorator implements AttributeDecoratorInterface
{
    public function supports(string $phpAttributeName): bool
    {
        return in_array($phpAttributeName, ['TYPO3\CMS\Extbase\Annotation\ORM\Cascade', 'Extbase\ORM\Cascade'], true);
    }

    public function decorate(Attribute $attribute): void
    {
        $cascadeRemove = new Array_([new ArrayItem(new String_('remove'), new String_('value'))]);

        if (! isset($attribute->args[0])) {
            $attribute->args[0] = new Arg($cascadeRemove);
        } else {
            $attribute->args[0]->value = $cascadeRemove;
        }
    }
}

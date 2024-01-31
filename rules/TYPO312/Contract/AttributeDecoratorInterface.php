<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO312\Contract;

use PhpParser\Node\Attribute;

interface AttributeDecoratorInterface
{
    public function supports(string $phpAttributeName): bool;

    public function decorate(Attribute $attribute): void;
}

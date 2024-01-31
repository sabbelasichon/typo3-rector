<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO312\AnnotationToAttribute;

use PhpParser\Node\Attribute;
use Ssch\TYPO3Rector\TYPO312\Contract\AttributeDecoratorInterface;

final class AttributeDecorator
{
    /**
     * @readonly
     * @var AttributeDecoratorInterface[]
     */
    private array $decorators;

    /**
     * @param AttributeDecoratorInterface[] $decorators
     */
    public function __construct(array $decorators)
    {
        $this->decorators = $decorators;
    }

    public function decorate(string $phpAttributeName, Attribute $attribute): void
    {
        foreach ($this->decorators as $decorator) {
            if ($decorator->supports($phpAttributeName)) {
                $decorator->decorate($attribute);
            }
        }
    }
}

<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\NodeAnalyzer\Command;

use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Property;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\Php80\NodeAnalyzer\PhpAttributeAnalyzer;
use Rector\Symfony\Enum\SymfonyAttribute;
use Ssch\TYPO3Rector\NodeAnalyzer\AttributeValueResolver;

final class CommandPropertyResolver
{
    /**
     * @readonly
     */
    private NodeNameResolver $nodeNameResolver;

    /**
     * @readonly
     */
    private PhpAttributeAnalyzer $phpAttributeAnalyzer;

    /**
     * @readonly
     */
    private AttributeValueResolver $attributeValueResolver;

    public function __construct(
        NodeNameResolver $nodeNameResolver,
        PhpAttributeAnalyzer $phpAttributeAnalyzer,
        AttributeValueResolver $attributeValueResolver
    ) {
        $this->nodeNameResolver = $nodeNameResolver;
        $this->phpAttributeAnalyzer = $phpAttributeAnalyzer;
        $this->attributeValueResolver = $attributeValueResolver;
    }

    public function resolveDefaultName(Class_ $class): ?string
    {
        foreach ($class->stmts as $key => $stmt) {
            if (! $stmt instanceof Property) {
                continue;
            }

            if (! $this->nodeNameResolver->isName($stmt->props[0], 'defaultName')) {
                continue;
            }

            $defaultName = $this->getValueFromProperty($stmt);
            if ($defaultName !== null) {
                // remove property
                unset($class->stmts[$key]);
                return $defaultName;
            }
        }

        return $this->defaultDefaultNameFromAttribute($class);
    }

    public function resolveDefaultDescription(Class_ $class): ?string
    {
        foreach ($class->stmts as $key => $stmt) {
            if (! $stmt instanceof Property) {
                continue;
            }

            if (! $this->nodeNameResolver->isName($stmt, 'defaultDescription')) {
                continue;
            }

            $defaultDescription = $this->getValueFromProperty($stmt);
            if ($defaultDescription !== null) {
                unset($class->stmts[$key]);
                return $defaultDescription;
            }
        }

        return $this->resolveDefaultDescriptionFromAttribute($class);
    }

    private function getValueFromProperty(Property $property): ?string
    {
        if (\count($property->props) !== 1) {
            return null;
        }

        $propertyProperty = $property->props[0];
        if ($propertyProperty->default instanceof String_) {
            return $propertyProperty->default->value;
        }

        return null;
    }

    private function defaultDefaultNameFromAttribute(Class_ $class): ?string
    {
        if (! $this->phpAttributeAnalyzer->hasPhpAttribute($class, SymfonyAttribute::AS_COMMAND)) {
            return null;
        }

        $defaultNameFromArgument = $this->attributeValueResolver->getArgumentValueFromAttribute($class, 0);
        if (\is_string($defaultNameFromArgument)) {
            return $defaultNameFromArgument;
        }

        return null;
    }

    private function resolveDefaultDescriptionFromAttribute(Class_ $class): ?string
    {
        if ($this->phpAttributeAnalyzer->hasPhpAttribute($class, SymfonyAttribute::AS_COMMAND)) {
            $defaultDescriptionFromArgument = $this->attributeValueResolver->getArgumentValueFromAttribute($class, 1);
            if (\is_string($defaultDescriptionFromArgument)) {
                return $defaultDescriptionFromArgument;
            }
        }

        return null;
    }
}

<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\NodeAnalyzer\Command;

use PhpParser\Node\Arg;
use PhpParser\Node\Attribute;
use PhpParser\Node\AttributeGroup;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Identifier;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Class_;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\PhpAttribute\NodeFactory\PhpAttributeGroupFactory;
use Rector\PhpParser\Node\NodeFactory;
use Rector\Symfony\Enum\SymfonyAttribute;

final class CommandAttributeManipulator
{
    /**
     * @readonly
     */
    private PhpAttributeGroupFactory $phpAttributeGroupFactory;

    /**
     * @readonly
     */
    private NodeFactory $nodeFactory;

    /**
     * @readonly
     */
    private NodeNameResolver $nodeNameResolver;

    public function __construct(
        PhpAttributeGroupFactory $phpAttributeGroupFactory,
        NodeFactory $nodeFactory,
        NodeNameResolver $nodeNameResolver
    ) {
        $this->phpAttributeGroupFactory = $phpAttributeGroupFactory;
        $this->nodeFactory = $nodeFactory;
        $this->nodeNameResolver = $nodeNameResolver;
    }

    public function replaceAsCommandAttribute(Class_ $class, AttributeGroup $createAttributeGroup): ?Class_
    {
        $hasAsCommandAttribute = \false;
        $replacedAsCommandAttribute = \false;
        foreach ($class->attrGroups as $attrGroup) {
            foreach ($attrGroup->attrs as $attribute) {
                if ($this->nodeNameResolver->isName($attribute->name, SymfonyAttribute::AS_COMMAND)) {
                    $hasAsCommandAttribute = \true;
                    $replacedAsCommandAttribute = $this->replaceArguments($attribute, $createAttributeGroup);
                }
            }
        }

        if ($hasAsCommandAttribute === \false) {
            $class->attrGroups[] = $createAttributeGroup;
            $replacedAsCommandAttribute = \true;
        }

        if ($replacedAsCommandAttribute === \false) {
            return null;
        }

        return $class;
    }

    public function createAttributeGroupAsCommand(
        string $defaultName,
        ?string $defaultDescription,
        ?Array_ $aliasesArray,
        ?bool $hidden
    ): AttributeGroup {
        $attributeGroup = $this->phpAttributeGroupFactory->createFromClass(SymfonyAttribute::AS_COMMAND);
        $attributeGroup->attrs[0]->args[] = new Arg(new String_($defaultName), false, false, [], new Identifier(
            'name'
        ));
        if ($defaultDescription !== null) {
            $attributeGroup->attrs[0]->args[] = new Arg(new String_(
                $defaultDescription
            ), false, false, [], new Identifier('description'));
        } elseif ($aliasesArray instanceof Array_) {
            $attributeGroup->attrs[0]->args[] = new Arg($this->nodeFactory->createNull());
        }

        if ($aliasesArray instanceof Array_) {
            $attributeGroup->attrs[0]->args[] = new Arg($aliasesArray, false, false, [], new Identifier('aliases'));
        }

        if ($hidden !== null) {
            $hiddenNode = $hidden ? $this->nodeFactory->createTrue() : $this->nodeFactory->createFalse();
            $attributeGroup->attrs[0]->args[] = new Arg($hiddenNode, false, false, [], new Identifier('hidden'));
        }

        return $attributeGroup;
    }

    private function replaceArguments(Attribute $attribute, AttributeGroup $createAttributeGroup): bool
    {
        $replacedAsCommandAttribute = \false;
        if (! $attribute->args[0]->value instanceof String_) {
            $attribute->args[0] = $createAttributeGroup->attrs[0]->args[0];
            $replacedAsCommandAttribute = \true;
        }

        if (! isset($attribute->args[1]) && isset($createAttributeGroup->attrs[0]->args[1])) {
            $attribute->args[1] = $createAttributeGroup->attrs[0]->args[1];
            $replacedAsCommandAttribute = \true;
        }

        if (! isset($attribute->args[2]) && isset($createAttributeGroup->attrs[0]->args[2])) {
            $attribute->args[2] = $createAttributeGroup->attrs[0]->args[2];
            $replacedAsCommandAttribute = \true;
        }

        if (! isset($attribute->args[3]) && isset($createAttributeGroup->attrs[0]->args[3])) {
            $attribute->args[3] = $createAttributeGroup->attrs[0]->args[3];
            $replacedAsCommandAttribute = \true;
        }

        return $replacedAsCommandAttribute;
    }
}

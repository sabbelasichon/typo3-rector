<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO312\v4;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Attribute;
use PhpParser\Node\AttributeGroup;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Identifier;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Property;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\ObjectType;
use Rector\Php80\NodeAnalyzer\PhpAttributeAnalyzer;
use Rector\PhpAttribute\NodeFactory\PhpAttributeGroupFactory;
use Rector\Rector\AbstractRector;
use Rector\Symfony\Enum\SymfonyAttribute;
use Rector\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Ssch\TYPO3Rector\Helper\ServiceDefinitionHelper;
use Ssch\TYPO3Rector\NodeAnalyzer\AttributeValueResolver;
use Ssch\TYPO3Rector\NodeAnalyzer\SetAliasesMethodCallExtractor;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.4.x/Important-101567-UseSymfonyAttributeToAutoconfigureCliCommands.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v4\CommandConfigurationToAttributeRector\CommandConfigurationToAttributeRectorTest
 */
final class CommandConfigurationToAttributeRector extends AbstractRector implements MinPhpVersionInterface, DocumentedRuleInterface
{
    /**
     * @readonly
     */
    private PhpAttributeGroupFactory $phpAttributeGroupFactory;

    /**
     * @readonly
     */
    private PhpAttributeAnalyzer $phpAttributeAnalyzer;

    /**
     * @readonly
     */
    private AttributeValueResolver $attributeValueResolver;

    /**
     * @readonly
     */
    private ReflectionProvider $reflectionProvider;

    /**
     * @readonly
     */
    private SetAliasesMethodCallExtractor $setAliasesMethodCallExtractor;

    /**
     * @readonly
     */
    private ServiceDefinitionHelper $serviceDefinitionHelper;

    private string $commandTagName = 'console.command';

    public function __construct(
        ServiceDefinitionHelper $symfonyCommandHelper,
        PhpAttributeGroupFactory $phpAttributeGroupFactory,
        PhpAttributeAnalyzer $phpAttributeAnalyzer,
        AttributeValueResolver $attributeValueResolver,
        ReflectionProvider $reflectionProvider,
        SetAliasesMethodCallExtractor $setAliasesMethodCallExtractor
    ) {
        $this->phpAttributeGroupFactory = $phpAttributeGroupFactory;
        $this->phpAttributeAnalyzer = $phpAttributeAnalyzer;
        $this->attributeValueResolver = $attributeValueResolver;
        $this->reflectionProvider = $reflectionProvider;
        $this->setAliasesMethodCallExtractor = $setAliasesMethodCallExtractor;
        $this->serviceDefinitionHelper = $symfonyCommandHelper;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::ATTRIBUTES;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Use Symfony attribute to autoconfigure cli commands', [new CodeSample(
            <<<'CODE_SAMPLE'
use Symfony\Component\Console\Command\Command;
class MySpecialCommand extends Command
{
}
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
#[AsCommand(name: 'my_special_command')]
class MySpecialCommand extends Command
{
}
CODE_SAMPLE
        )]);
    }

    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    /**
     * @param Class_ $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isObjectType($node, new ObjectType('Symfony\\Component\\Console\\Command\\Command'))) {
            return null;
        }

        if (! $this->reflectionProvider->hasClass(SymfonyAttribute::AS_COMMAND)) {
            return null;
        }

        if ($this->phpAttributeAnalyzer->hasPhpAttribute($node, SymfonyAttribute::AS_COMMAND)) {
            return null;
        }

        $commands = $this->serviceDefinitionHelper->getServiceDefinitionsByTagName($this->commandTagName);
        if ($commands === []) {
            return null;
        }

        $options = null;
        foreach ($commands as $command) {
            if ($this->isName($node, $command->getClass() ?? $command->getId())) {
                $options = $this->serviceDefinitionHelper->extractOptionsFromServiceDefinition(
                    $command,
                    $this->commandTagName
                );
            }
        }

        if ($options === null) {
            return null;
        }

        // Non schedulable commands cannot be configured via attributes
        $schedulable = $options['schedulable'] ?? true;

        if ((bool) $schedulable === false) {
            return null;
        }

        if (! isset($options['command'])) {
            return null;
        }

        $defaultDescription = $this->resolveDefaultDescription($node) ?? $options['description'] ?? null;
        $defaultName = $this->resolveDefaultName($node) ?? $options['command'];
        $hidden = $options['hidden'] ?? null;
        $aliasesArray = $this->setAliasesMethodCallExtractor->resolveCommandAliasesFromAttributeOrSetter($node);
        return $this->replaceAsCommandAttribute(
            $node,
            $this->createAttributeGroupAsCommand($defaultName, $defaultDescription, $aliasesArray, (bool) $hidden)
        );
    }

    private function createAttributeGroupAsCommand(
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

    private function resolveDefaultName(Class_ $class): ?string
    {
        foreach ($class->stmts as $key => $stmt) {
            if (! $stmt instanceof Property) {
                continue;
            }

            if (! $this->isName($stmt->props[0], 'defaultName')) {
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

    private function resolveDefaultDescription(Class_ $class): ?string
    {
        foreach ($class->stmts as $key => $stmt) {
            if (! $stmt instanceof Property) {
                continue;
            }

            if (! $this->isName($stmt, 'defaultDescription')) {
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

    private function replaceAsCommandAttribute(Class_ $class, AttributeGroup $createAttributeGroup): ?Class_
    {
        $hasAsCommandAttribute = \false;
        $replacedAsCommandAttribute = \false;
        foreach ($class->attrGroups as $attrGroup) {
            foreach ($attrGroup->attrs as $attribute) {
                if ($this->isName($attribute->name, SymfonyAttribute::AS_COMMAND)) {
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
}

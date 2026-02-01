<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO313\v0;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Attribute;
use PhpParser\Node\AttributeGroup;
use PhpParser\Node\Expr;
use PhpParser\Node\Identifier;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Reflection\ReflectionProvider;
use Rector\Php80\NodeAnalyzer\PhpAttributeAnalyzer;
use Rector\PhpAttribute\NodeFactory\PhpAttributeGroupFactory;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\Rector\AbstractRector;
use Rector\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Ssch\TYPO3Rector\Helper\ServiceDefinitionHelper;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/13.0/Feature-101544-IntroducePHPAttributeToAutoconfigureEventListeners.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v13\v0\EventListenerConfigurationToAttributeRector\EventListenerConfigurationToAttributeRectorTest
 */
final class EventListenerConfigurationToAttributeRector extends AbstractRector implements MinPhpVersionInterface, DocumentedRuleInterface
{
    private const EVENT_LISTENER_TAG_NAME = 'event.listener';

    private const ATTRIBUTE_CLASS = 'TYPO3\CMS\Core\Attribute\AsEventListener';

    /**
     * @readonly
     */
    private ReflectionProvider $reflectionProvider;

    /**
     * @readonly
     */
    private ServiceDefinitionHelper $serviceDefinitionHelper;

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
    private ValueResolver $valueResolver;

    public function __construct(
        ReflectionProvider $reflectionProvider,
        ServiceDefinitionHelper $serviceDefinitionHelper,
        PhpAttributeGroupFactory $phpAttributeGroupFactory,
        PhpAttributeAnalyzer $phpAttributeAnalyzer,
        ValueResolver $valueResolver
    ) {
        $this->reflectionProvider = $reflectionProvider;
        $this->serviceDefinitionHelper = $serviceDefinitionHelper;
        $this->phpAttributeGroupFactory = $phpAttributeGroupFactory;
        $this->phpAttributeAnalyzer = $phpAttributeAnalyzer;
        $this->valueResolver = $valueResolver;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            <<<'DESCRRIPTION'
Use AsEventListener attribute

To run this rule, you need to do the following steps:
- Require `"ssch/typo3-debug-dump-pass": "^0.0.2"` in your composer.json in the main TYPO3 project
- Add `->withSymfonyContainerXml(__DIR__ . '/var/cache/development/App_KernelDevelopmentDebugContainer.xml')` in your rector config file.
- Clear the TYPO3 cache via cmd: `vendor/bin/typo3 cache:flush` to create the `App_KernelDevelopmentDebugContainer.xml` file.
- Finally run Rector.
DESCRRIPTION
            ,
            [new CodeSample(
                <<<'CODE_SAMPLE'
namespace MyVendor\MyExtension\EventListener;

use TYPO3\CMS\Core\Mail\Event\AfterMailerInitializationEvent;

final class NullMailer
{
    public function __invoke(AfterMailerInitializationEvent $event): void
    {
    }
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
namespace MyVendor\MyExtension\EventListener;

use TYPO3\CMS\Core\Attribute\AsEventListener;
use TYPO3\CMS\Core\Mail\Event\AfterMailerInitializationEvent;

#[AsEventListener(identifier: 'my-extension/after-mailer-initialization')]
final class NullMailer
{
    public function __invoke(AfterMailerInitializationEvent $event): void
    {
    }
}
CODE_SAMPLE
            )]
        );
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::ATTRIBUTES;
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
        // Ignore anonymous classes
        if ($node->name === null) {
            return null;
        }

        if (! $this->reflectionProvider->hasClass(self::ATTRIBUTE_CLASS)) {
            return null;
        }

        $serviceDefinitions = $this->serviceDefinitionHelper->getServiceDefinitionsByTagName(
            self::EVENT_LISTENER_TAG_NAME
        );
        if ($serviceDefinitions === []) {
            return null;
        }

        $hasChanged = false;

        foreach ($serviceDefinitions as $serviceDefinition) {
            if (! $this->isName($node, $serviceDefinition->getClass() ?? $serviceDefinition->getId())) {
                continue;
            }

            // Loop through all tags, as one service can have multiple event listeners
            foreach ($serviceDefinition->getTags() as $tag) {
                if ($tag->getName() !== self::EVENT_LISTENER_TAG_NAME) {
                    continue;
                }

                $options = $tag->getData();

                $method = $options['method'] ?? null;
                $identifier = $options['identifier'] ?? null;
                $event = $options['event'] ?? null;
                $before = $options['before'] ?? null;
                $after = $options['after'] ?? null;

                if ($method !== null) {
                    // Specific method listener
                    $removed = $this->removeClassAttributeForMethod($node, (string) $method);
                    $added = $this->addAttributeToMethod($node, (string) $method, $identifier, $event, $before, $after);
                    if ($removed || $added) {
                        $hasChanged = true;
                    }
                } elseif (! $this->phpAttributeAnalyzer->hasPhpAttribute($node, self::ATTRIBUTE_CLASS)) {
                    // Class level listener
                    $this->replaceAsEventListenerAttribute(
                        $node,
                        $this->createAttributeGroupAsEventListener($identifier, $event, $before, $after)
                    );
                    $hasChanged = true;
                }
            }
        }

        return $hasChanged ? $node : null;
    }

    private function removeClassAttributeForMethod(Class_ $class, string $methodName): bool
    {
        $hasChanged = false;
        $newAttrGroups = [];

        foreach ($class->attrGroups as $attrGroup) {
            $newAttrs = [];
            foreach ($attrGroup->attrs as $attribute) {
                if ($this->shouldRemoveAttribute($attribute, $methodName)) {
                    $hasChanged = true;
                    continue;
                }

                $newAttrs[] = $attribute;
            }

            if ($newAttrs !== []) {
                $attrGroup->attrs = $newAttrs;
                $newAttrGroups[] = $attrGroup;
            }
        }

        if ($hasChanged) {
            $class->attrGroups = $newAttrGroups;
        }

        return $hasChanged;
    }

    private function shouldRemoveAttribute(Attribute $attribute, string $methodName): bool
    {
        if (! $this->isName($attribute->name, self::ATTRIBUTE_CLASS)) {
            return false;
        }

        foreach ($attribute->args as $key => $arg) {
            // Check for named argument 'method'
            if ($arg->name instanceof Identifier && $this->isName($arg->name, 'method')) {
                return $this->isStringValueMatch($arg->value, $methodName);
            }

            // Check for positional argument (index 2 is 'method')
            if ($arg->name === null && $key === 2) {
                return $this->isStringValueMatch($arg->value, $methodName);
            }
        }

        return false;
    }

    private function isStringValueMatch(Expr $expr, string $expectedValue): bool
    {
        if ($expr instanceof String_) {
            return $expr->value === $expectedValue;
        }

        $resolvedValue = $this->valueResolver->getValue($expr);
        return $resolvedValue === $expectedValue;
    }

    private function addAttributeToMethod(
        Class_ $node,
        string $methodName,
        ?string $identifier,
        ?string $event,
        ?string $before,
        ?string $after
    ): bool {
        $targetMethod = $this->findMethod($node, $methodName);
        if (! $targetMethod instanceof ClassMethod) {
            return false;
        }

        if ($this->phpAttributeAnalyzer->hasPhpAttribute($targetMethod, self::ATTRIBUTE_CLASS)) {
            return false;
        }

        $targetMethod->attrGroups[] = $this->createAttributeGroupAsEventListener($identifier, $event, $before, $after);
        return true;
    }

    private function findMethod(Class_ $class, string $methodName): ?ClassMethod
    {
        foreach ($class->getMethods() as $method) {
            if ($this->isName($method, $methodName)) {
                return $method;
            }
        }

        return null;
    }

    private function createAttributeGroupAsEventListener(
        ?string $identifier,
        ?string $event,
        ?string $before,
        ?string $after
    ): AttributeGroup {
        $attributeGroup = $this->phpAttributeGroupFactory->createFromClass(self::ATTRIBUTE_CLASS);

        $simpleOptions = array_filter([
            'identifier' => $identifier,
            'event' => $event,
            'before' => $before,
            'after' => $after,
        ]);

        foreach ($simpleOptions as $name => $simpleOption) {
            if ($name === 'event' && $event !== null) {
                $eventClass = $this->nodeFactory->createClassConstReference($event);
                $attributeGroup->attrs[0]->args[] = new Arg($eventClass, false, false, [], new Identifier('event'));
            } else {
                $attributeGroup->attrs[0]->args[] = new Arg(new String_(
                    $simpleOption
                ), false, false, [], new Identifier($name));
            }
        }

        return $attributeGroup;
    }

    private function replaceAsEventListenerAttribute(Class_ $class, AttributeGroup $createAttributeGroup): void
    {
        $hasAttribute = false;
        foreach ($class->attrGroups as $attrGroup) {
            foreach ($attrGroup->attrs as $attribute) {
                if ($this->isName($attribute->name, self::ATTRIBUTE_CLASS)) {
                    $hasAttribute = true;
                    $this->replaceArguments($attribute, $createAttributeGroup);
                }
            }
        }

        if (! $hasAttribute) {
            $class->attrGroups[] = $createAttributeGroup;
        }
    }

    private function replaceArguments(Attribute $attribute, AttributeGroup $createAttributeGroup): void
    {
        for ($i = 0; $i <= 4; ++$i) {
            if (isset($createAttributeGroup->attrs[0]->args[$i]) && ! isset($attribute->args[$i])) {
                $attribute->args[$i] = $createAttributeGroup->attrs[0]->args[$i];
            }
        }
    }
}

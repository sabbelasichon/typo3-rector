<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO313\v0;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Attribute;
use PhpParser\Node\AttributeGroup;
use PhpParser\Node\Identifier;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Reflection\ReflectionProvider;
use Rector\Php80\NodeAnalyzer\PhpAttributeAnalyzer;
use Rector\PhpAttribute\NodeFactory\PhpAttributeGroupFactory;
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

    public function __construct(
        ReflectionProvider $reflectionProvider,
        ServiceDefinitionHelper $serviceDefinitionHelper,
        PhpAttributeGroupFactory $phpAttributeGroupFactory,
        PhpAttributeAnalyzer $phpAttributeAnalyzer
    ) {
        $this->reflectionProvider = $reflectionProvider;
        $this->serviceDefinitionHelper = $serviceDefinitionHelper;
        $this->phpAttributeGroupFactory = $phpAttributeGroupFactory;
        $this->phpAttributeAnalyzer = $phpAttributeAnalyzer;
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

#[AsEventListener(
    identifier: 'my-extension/null-mailer'
)]
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
        if (! $this->reflectionProvider->hasClass('TYPO3\CMS\Core\Attribute\AsEventListener')) {
            return null;
        }

        if ($this->phpAttributeAnalyzer->hasPhpAttribute($node, 'TYPO3\CMS\Core\Attribute\AsEventListener')) {
            return null;
        }

        $eventListeners = $this->serviceDefinitionHelper->getServiceDefinitionsByTagName(self::EVENT_LISTENER_TAG_NAME);
        if ($eventListeners === []) {
            return null;
        }

        $options = null;
        foreach ($eventListeners as $eventListener) {
            if ($this->isName($node, $eventListener->getClass() ?? $eventListener->getId())) {
                $options = $this->serviceDefinitionHelper->extractOptionsFromServiceDefinition(
                    $eventListener,
                    self::EVENT_LISTENER_TAG_NAME
                );
            }
        }

        $identifier = $options['identifier'] ?? null;
        $event = $options['event'] ?? null;
        $method = $options['method'] ?? null;
        $before = $options['before'] ?? null;
        $after = $options['after'] ?? null;

        if ($options === null) {
            return null;
        }

        return $this->replaceAsEventListenerAttribute(
            $node,
            $this->createAttributeGroupAsEventListener($identifier, $event, $method, $before, $after)
        );
    }

    private function createAttributeGroupAsEventListener(
        ?string $identifier,
        ?string $event,
        ?string $method,
        ?string $before,
        ?string $after
    ): AttributeGroup {
        $attributeGroup = $this->phpAttributeGroupFactory->createFromClass('TYPO3\CMS\Core\Attribute\AsEventListener');

        $simpleOptions = array_filter([
            'identifier' => $identifier,
            'event' => $event,
            'method' => $method,
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

    private function replaceAsEventListenerAttribute(Class_ $class, AttributeGroup $createAttributeGroup): ?Class_
    {
        $hasAsEventListenerAttribute = \false;
        $replacedAsEventListenerAttribute = \false;
        foreach ($class->attrGroups as $attrGroup) {
            foreach ($attrGroup->attrs as $attribute) {
                if ($this->isName($attribute->name, 'TYPO3\CMS\Core\Attribute\AsEventListener')) {
                    $hasAsEventListenerAttribute = \true;
                    $replacedAsEventListenerAttribute = $this->replaceArguments($attribute, $createAttributeGroup);
                }
            }
        }

        if ($hasAsEventListenerAttribute === \false) {
            $class->attrGroups[] = $createAttributeGroup;
            $replacedAsEventListenerAttribute = \true;
        }

        if ($replacedAsEventListenerAttribute === \false) {
            return null;
        }

        return $class;
    }

    private function replaceArguments(Attribute $attribute, AttributeGroup $createAttributeGroup): bool
    {
        $replacedAsEventListenerAttribute = \false;
        if (! $attribute->args[0]->value instanceof String_) {
            $attribute->args[0] = $createAttributeGroup->attrs[0]->args[0];
            $replacedAsEventListenerAttribute = \true;
        }

        if (! isset($attribute->args[1]) && isset($createAttributeGroup->attrs[0]->args[1])) {
            $attribute->args[1] = $createAttributeGroup->attrs[0]->args[1];
            $replacedAsEventListenerAttribute = \true;
        }

        if (! isset($attribute->args[2]) && isset($createAttributeGroup->attrs[0]->args[2])) {
            $attribute->args[2] = $createAttributeGroup->attrs[0]->args[2];
            $replacedAsEventListenerAttribute = \true;
        }

        if (! isset($attribute->args[3]) && isset($createAttributeGroup->attrs[0]->args[3])) {
            $attribute->args[3] = $createAttributeGroup->attrs[0]->args[3];
            $replacedAsEventListenerAttribute = \true;
        }

        if (! isset($attribute->args[4]) && isset($createAttributeGroup->attrs[0]->args[4])) {
            $attribute->args[4] = $createAttributeGroup->attrs[0]->args[4];
            $replacedAsEventListenerAttribute = \true;
        }

        return $replacedAsEventListenerAttribute;
    }
}

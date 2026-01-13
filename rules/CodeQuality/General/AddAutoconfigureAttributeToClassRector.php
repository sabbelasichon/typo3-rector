<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\CodeQuality\General;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Reflection\ReflectionProvider;
use Rector\Php80\NodeAnalyzer\PhpAttributeAnalyzer;
use Rector\PhpAttribute\NodeFactory\PhpAttributeGroupFactory;
use Rector\Rector\AbstractRector;
use Rector\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Ssch\TYPO3Rector\Contract\NoChangelogRequiredInterface;
use Ssch\TYPO3Rector\Helper\ServiceDefinitionHelper;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\AddAutoconfigureAttributeToClassRector\AddAutoconfigureAttributeToClassRectorTest
 */
final class AddAutoconfigureAttributeToClassRector extends AbstractRector implements MinPhpVersionInterface, DocumentedRuleInterface, NoChangelogRequiredInterface
{
    private const AUTOCONFIGURE = 'Symfony\Component\DependencyInjection\Attribute\Autoconfigure';

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

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::ATTRIBUTES;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            <<<'DESCRRIPTION'
Add Autoconfigure attribute for public or non-shared services

To run this rule, you need to do the following steps:
- Require `"ssch/typo3-debug-dump-pass": "^0.0.2"` in your composer.json in the main TYPO3 project
- Add `->withSymfonyContainerXml(__DIR__ . '/var/cache/development/App_KernelDevelopmentDebugContainer.xml')` in your rector config file.
- Clear the TYPO3 cache via cmd: `vendor/bin/typo3 cache:flush` to create the `App_KernelDevelopmentDebugContainer.xml` file.
- Finally run Rector.
DESCRRIPTION

            ,
            [new CodeSample(
                <<<'CODE_SAMPLE'
class MyService
{
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

#[Autoconfigure(public: true)]
class MyService
{
}
CODE_SAMPLE
            ), new CodeSample(
                <<<'CODE_SAMPLE'
class NotSharedService
{
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

#[Autoconfigure(public: true, shared: false)]
class NotSharedService
{
}
CODE_SAMPLE
            )]
        );
    }

    /**
     * @return array<class-string<Node>>
     */
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

        $className = $this->getName($node);
        if ($className === null) {
            return null;
        }

        if (! $this->reflectionProvider->hasClass(self::AUTOCONFIGURE)) {
            return null;
        }

        // Do not add the attribute if it is already present
        if ($this->phpAttributeAnalyzer->hasPhpAttribute($node, self::AUTOCONFIGURE)) {
            return null;
        }

        $isPublic = $this->serviceDefinitionHelper->isPublicService($className);
        $isNotShared = $this->serviceDefinitionHelper->isNotSharedService($className);

        // If it is neither public nor non-shared, we don't need to add the attribute
        if (! $isPublic && ! $isNotShared) {
            return null;
        }

        $attributeGroup = $this->phpAttributeGroupFactory->createFromClass(self::AUTOCONFIGURE);

        // Add public: true if the service is public
        if ($isPublic) {
            $attributeGroup->attrs[0]->args[] = new Arg(
                $this->nodeFactory->createTrue(),
                false,
                false,
                [],
                new Identifier('public')
            );
        }

        // Add shared: false if the service is not shared
        if ($isNotShared) {
            $attributeGroup->attrs[0]->args[] = new Arg(
                $this->nodeFactory->createFalse(),
                false,
                false,
                [],
                new Identifier('shared')
            );
        }

        $node->attrGroups[] = $attributeGroup;

        return $node;
    }
}

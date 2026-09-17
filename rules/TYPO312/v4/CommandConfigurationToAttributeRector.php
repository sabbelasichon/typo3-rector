<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO312\v4;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\ObjectType;
use Rector\Php80\NodeAnalyzer\PhpAttributeAnalyzer;
use Rector\Rector\AbstractRector;
use Rector\Symfony\Enum\SymfonyAttribute;
use Rector\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Ssch\TYPO3Rector\Helper\ServiceDefinitionHelper;
use Ssch\TYPO3Rector\NodeAnalyzer\Command\CommandAttributeManipulator;
use Ssch\TYPO3Rector\NodeAnalyzer\Command\CommandPropertyResolver;
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
    private const COMMAND_TAG_NAME = 'console.command';

    /**
     * @readonly
     */
    private PhpAttributeAnalyzer $phpAttributeAnalyzer;

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

    /**
     * @readonly
     */
    private CommandAttributeManipulator $commandAttributeManipulator;

    /**
     * @readonly
     */
    private CommandPropertyResolver $commandPropertyResolver;

    public function __construct(
        ServiceDefinitionHelper $symfonyCommandHelper,
        PhpAttributeAnalyzer $phpAttributeAnalyzer,
        ReflectionProvider $reflectionProvider,
        SetAliasesMethodCallExtractor $setAliasesMethodCallExtractor,
        CommandAttributeManipulator $commandAttributeManipulator,
        CommandPropertyResolver $commandPropertyResolver
    ) {
        $this->phpAttributeAnalyzer = $phpAttributeAnalyzer;
        $this->reflectionProvider = $reflectionProvider;
        $this->setAliasesMethodCallExtractor = $setAliasesMethodCallExtractor;
        $this->serviceDefinitionHelper = $symfonyCommandHelper;
        $this->commandAttributeManipulator = $commandAttributeManipulator;
        $this->commandPropertyResolver = $commandPropertyResolver;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::ATTRIBUTES;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            <<<'DESCRRIPTION'
Use Symfony attribute to autoconfigure cli commands

To run this rule, you need to do the following steps:
- Require `"ssch/typo3-debug-dump-pass": "^0.0.3"` in your composer.json in the main TYPO3 project
- Add `->withSymfonyContainerXml(__DIR__ . '/var/cache/development/App_KernelDevelopmentDebugContainer.xml')` in your rector config file.
- Clear the TYPO3 cache via cmd: `vendor/bin/typo3 cache:flush` to create the `App_KernelDevelopmentDebugContainer.xml` file.
- Finally run Rector.
DESCRRIPTION
            ,
            [new CodeSample(
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
            )]
        );
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

        if (! $this->isObjectType($node, new ObjectType('Symfony\Component\Console\Command\Command'))) {
            return null;
        }

        if (! $this->reflectionProvider->hasClass(SymfonyAttribute::AS_COMMAND)) {
            return null;
        }

        // Do not add the attribute if it is already present
        if ($this->phpAttributeAnalyzer->hasPhpAttribute($node, SymfonyAttribute::AS_COMMAND)) {
            return null;
        }

        $commands = $this->serviceDefinitionHelper->getServiceDefinitionsByTagName(self::COMMAND_TAG_NAME);
        if ($commands === []) {
            return null;
        }

        $options = null;
        foreach ($commands as $command) {
            if ($this->isName($node, $command->getClass() ?? $command->getId())) {
                $options = $this->serviceDefinitionHelper->extractOptionsFromServiceDefinition(
                    $command,
                    self::COMMAND_TAG_NAME
                );
            }
        }

        if ($options === null) {
            return null;
        }

        // Non schedulable commands cannot be configured via attributes
        $schedulable = ($options['schedulable'] ?? 'true') === 'true';
        if ($schedulable === false) {
            return null;
        }

        if (! isset($options['command'])) {
            return null;
        }

        $defaultDescription = $this->commandPropertyResolver->resolveDefaultDescription(
            $node
        ) ?? $options['description'] ?? null;
        $defaultName = $this->commandPropertyResolver->resolveDefaultName($node) ?? $options['command'];
        $hidden = $options['hidden'] ?? null;
        $aliasesArray = $this->setAliasesMethodCallExtractor->resolveCommandAliasesFromAttributeOrSetter($node);
        return $this->commandAttributeManipulator->replaceAsCommandAttribute(
            $node,
            $this->commandAttributeManipulator->createAttributeGroupAsCommand(
                $defaultName,
                $defaultDescription,
                $aliasesArray,
                (bool) $hidden
            )
        );
    }
}

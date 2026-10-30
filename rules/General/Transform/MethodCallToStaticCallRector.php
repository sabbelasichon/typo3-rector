<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\General\Transform;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\Rector\AbstractRector;
use Rector\RuleDoc\CodeSample\ConfiguredCodeSample;
use Rector\RuleDoc\Contract\DocumentedRuleInterface;
use Rector\RuleDoc\RuleDefinition;
use Rector\Transform\ValueObject\MethodCallToStaticCall;
use Ssch\TYPO3Rector\Contract\NoChangelogRequiredInterface;
use Webmozart\Assert\Assert;

/**
 * @see \Ssch\TYPO3Rector\Tests\Rector\General\Transform\MethodCallToStaticCallRector\MethodCallToStaticCallRectorTest
 */
final class MethodCallToStaticCallRector extends AbstractRector implements ConfigurableRectorInterface, NoChangelogRequiredInterface, DocumentedRuleInterface
{
    /**
     * @var MethodCallToStaticCall[]
     */
    private array $methodCallsToStaticCalls = [];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Change method call to desired static call', [
            new ConfiguredCodeSample(
                '$anotherDependency->process(\'value\');',
                "StaticCaller::anotherMethod('value');",
                [new MethodCallToStaticCall('AnotherDependency', 'process', 'StaticCaller', 'anotherMethod')]
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    /**
     * @param MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        foreach ($this->methodCallsToStaticCalls as $methodCallToStaticCall) {
            if (! $this->isName($node->name, $methodCallToStaticCall->getOldMethod())) {
                continue;
            }

            if (! $this->isObjectType($node->var, $methodCallToStaticCall->getOldObjectType())) {
                continue;
            }

            return $this->nodeFactory->createStaticCall(
                $methodCallToStaticCall->getNewClass(),
                $methodCallToStaticCall->getNewMethod(),
                $node->args
            );
        }

        return null;
    }

    /**
     * @param MethodCallToStaticCall[] $configuration
     */
    public function configure(array $configuration): void
    {
        Assert::allIsAOf($configuration, MethodCallToStaticCall::class);
        $this->methodCallsToStaticCalls = $configuration;
    }
}

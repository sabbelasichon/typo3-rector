<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\General\Renaming;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name\FullyQualified;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\Rector\AbstractRector;
use Rector\Renaming\ValueObject\RenameStaticMethod;
use Rector\RuleDoc\CodeSample\ConfiguredCodeSample;
use Rector\RuleDoc\Contract\DocumentedRuleInterface;
use Rector\RuleDoc\RuleDefinition;
use Ssch\TYPO3Rector\Contract\NoChangelogRequiredInterface;
use Webmozart\Assert\Assert;

/**
 * @see \Ssch\TYPO3Rector\Tests\Rector\General\Renaming\RenameStaticMethodRector\RenameStaticMethodRectorTest
 */
final class RenameStaticMethodRector extends AbstractRector implements ConfigurableRectorInterface, NoChangelogRequiredInterface, DocumentedRuleInterface
{
    /**
     * @var RenameStaticMethod[]
     */
    private array $staticMethodRenames = [];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Turn static method names to new ones', [
            new ConfiguredCodeSample(
                'SomeClass::oldStaticMethod();',
                'AnotherExampleClass::newStaticMethod();',
                [new RenameStaticMethod('SomeClass', 'oldStaticMethod', 'AnotherExampleClass', 'newStaticMethod')]
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [StaticCall::class];
    }

    /**
     * @param StaticCall $node
     */
    public function refactor(Node $node): ?Node
    {
        foreach ($this->staticMethodRenames as $staticMethodRename) {
            if (! $this->isName($node->name, $staticMethodRename->getOldMethod())) {
                continue;
            }

            if (! $this->isObjectType($node->class, $staticMethodRename->getOldObjectType())) {
                continue;
            }

            $node->name = new Identifier($staticMethodRename->getNewMethod());
            if ($staticMethodRename->hasClassChanged()) {
                $node->class = new FullyQualified($staticMethodRename->getNewClass());
            }

            return $node;
        }

        return null;
    }

    /**
     * @param RenameStaticMethod[] $configuration
     */
    public function configure(array $configuration): void
    {
        Assert::allIsAOf($configuration, RenameStaticMethod::class);
        $this->staticMethodRenames = $configuration;
    }
}

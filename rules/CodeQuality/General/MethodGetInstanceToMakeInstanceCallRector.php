<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\CodeQuality\General;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use PHPStan\Type\ObjectType;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\Rector\AbstractRector;
use Ssch\TYPO3Rector\Contract\NoChangelogRequiredInterface;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

/**
 * @see \Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\MethodGetInstanceToMakeInstanceCallRector\MethodGetInstanceToMakeInstanceCallRectorTest
 */
final class MethodGetInstanceToMakeInstanceCallRector extends AbstractRector implements ConfigurableRectorInterface, NoChangelogRequiredInterface, DocumentedRuleInterface
{
    /**
     * @var string
     */
    public const CLASSES_GET_INSTANCE_TO_MAKE_INSTANCE = 'classes-get-instance-to-make-instance';

    /**
     * @var array<string, string[]>
     */
    private const EXAMPLE_CONFIGURATION = [
        self::CLASSES_GET_INSTANCE_TO_MAKE_INSTANCE => ['SomeClass'],
    ];

    /**
     * @var string[]
     */
    private array $classes = [];

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
        if ($this->shouldSkip($node)) {
            return null;
        }

        $className = $this->getName($node->class);

        if ($className === null) {
            return null;
        }

        $class = $this->nodeFactory->createClassConstReference($className);

        return $this->nodeFactory->createStaticCall(
            'TYPO3\\CMS\\Core\\Utility\\GeneralUtility',
            'makeInstance',
            [$class]
        );
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Use `GeneralUtility::makeInstance()` instead of `getInstance` call', [
            new ConfiguredCodeSample(
                <<<'CODE_SAMPLE'
$instance = TYPO3\CMS\Core\Resource\Index\ExtractorRegistry::getInstance();
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Resource\Index\ExtractorRegistry;

$instance = GeneralUtility::makeInstance(ExtractorRegistry::class);
CODE_SAMPLE
                ,
                self::EXAMPLE_CONFIGURATION
            ),
        ]);
    }

    /**
     * @param mixed[] $configuration
     */
    public function configure(array $configuration): void
    {
        $classes = $configuration[self::CLASSES_GET_INSTANCE_TO_MAKE_INSTANCE] ?? $configuration;
        Assert::isArray($classes);
        Assert::allString($classes);

        $this->classes = $classes;
    }

    private function shouldSkip(StaticCall $staticCall): bool
    {
        if ($this->classes === []) {
            return true;
        }

        if (! $this->isName($staticCall->name, 'getInstance')) {
            return true;
        }

        foreach ($this->classes as $class) {
            if ($this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
                $staticCall,
                new ObjectType($class)
            )) {
                return false;
            }
        }

        return true;
    }
}

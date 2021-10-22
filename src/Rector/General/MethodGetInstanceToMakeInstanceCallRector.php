<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\General;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use PHPStan\Type\ObjectType;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Webmozart\Assert\Assert;

/**
 * @see \Ssch\TYPO3Rector\Tests\Rector\General\MethodGetInstanceToMakeInstanceCallRector\MethodGetInstanceToMakeInstanceCallRectorTest
 */
final class MethodGetInstanceToMakeInstanceCallRector extends AbstractRector implements ConfigurableRectorInterface
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

        $className = $this->nodeNameResolver->getName($node->class);

        if (null === $className) {
            return null;
        }

        $class = $this->nodeFactory->createClassConstReference($className);

        return $this->nodeFactory->createStaticCall(GeneralUtility::class, 'makeInstance', [$class]);
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Use GeneralUtility::makeInstance instead of getInstance call', [
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

    public function configure(array $configuration): void
    {
        $classes = $configuration[self::CLASSES_GET_INSTANCE_TO_MAKE_INSTANCE] ?? [];
        Assert::allString($classes);
        $this->classes = $classes;
    }

    private function shouldSkip(StaticCall $node): bool
    {
        if ([] === $this->classes) {
            return true;
        }

        if (! $this->isName($node->name, 'getInstance')) {
            return true;
        }

        foreach ($this->classes as $class) {
            if ($this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType($node, new ObjectType($class))) {
                return false;
            }
        }

        return true;
    }
}

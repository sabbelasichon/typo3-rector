<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\CodeQuality\General;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\ObjectType;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\NodeManipulator\ClassDependencyManipulator;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\PostRector\ValueObject\PropertyMetadata;
use Rector\Rector\AbstractRector;
use Ssch\TYPO3Rector\Contract\NoChangelogRequiredInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

/**
 * @see \Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\GeneralUtilityMakeInstanceToConstructorPropertyRector\GeneralUtilityMakeInstanceToConstructorPropertyRectorTest
 */
final class GeneralUtilityMakeInstanceToConstructorPropertyRector extends AbstractRector implements ConfigurableRectorInterface, NoChangelogRequiredInterface
{
    /**
     * @var string
     */
    public const ALLOWED_CLASSES = 'allowed_classes';

    /**
     * @var string[]
     */
    private array $allowedClasses = [
        'TYPO3\CMS\Core\Configuration\Features',
        'TYPO3\CMS\Core\Context\Context',
        'TYPO3\CMS\Core\Database\ConnectionPool',
        'TYPO3\CMS\Core\Localization\LanguageServiceFactory',
        'TYPO3\CMS\Core\TimeTracker\TimeTracker',
        'TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder',
    ];

    /**
     * @readonly
     */
    private ClassDependencyManipulator $classDependencyManipulator;

    /**
     * @readonly
     */
    private ReflectionProvider $reflectionProvider;

    /**
     * @readonly
     */
    private ValueResolver $valueResolver;

    public function __construct(
        ClassDependencyManipulator $classDependencyManipulator,
        ReflectionProvider $reflectionProvider,
        ValueResolver $valueResolver
    ) {
        $this->classDependencyManipulator = $classDependencyManipulator;
        $this->reflectionProvider = $reflectionProvider;
        $this->valueResolver = $valueResolver;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Move GeneralUtility::makeInstance calls to constructor injection',
            [
                new ConfiguredCodeSample(
                    <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class Service
{
    public function myMethod(): void
    {
        GeneralUtility::makeInstance(Context::class)->getAspect('frontend.user');
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Context\Context;

class Service
{
    private Context $context;

    public function __construct(Context $context)
    {
        $this->context = $context;
    }

    public function myMethod(): void
    {
        $this->context->getAspect('frontend.user');
    }
}
CODE_SAMPLE
                    ,
                    [
                        self::ALLOWED_CLASSES => ['TYPO3\CMS\Core\Context\Context'],
                    ]
                ),
            ]
        );
    }

    /**
     * @return class-string[]
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
        if ($this->shouldSkip($node)) {
            return null;
        }

        $hasChanged = false;

        $this->traverseNodesWithCallable($node->stmts, function (Node $subNode) use ($node, &$hasChanged) {
            if (! $subNode instanceof StaticCall) {
                return null;
            }

            if (! $this->isObjectType($subNode->class, new ObjectType('TYPO3\CMS\Core\Utility\GeneralUtility'))) {
                return null;
            }

            if (! $this->isName($subNode->name, 'makeInstance')) {
                return null;
            }

            if (! isset($subNode->args[0])) {
                return null;
            }

            $className = $this->valueResolver->getValue($subNode->args[0]->value);
            if (! is_string($className)) {
                return null;
            }

            if ($this->allowedClasses !== [] && ! in_array($className, $this->allowedClasses, true)) {
                return null;
            }

            // Derive a property name from the class name (e.g., Context -> $context)
            $shortClassName = $this->nodeNameResolver->getShortName($className);
            $propertyName = lcfirst($shortClassName);

            $propertyMetadata = new PropertyMetadata(
                $propertyName,
                new ObjectType($className),
                Class_::MODIFIER_PRIVATE
            );

            $this->classDependencyManipulator->addConstructorDependency($node, $propertyMetadata);

            $hasChanged = true;

            return $this->nodeFactory->createPropertyFetch('this', $propertyName);
        });

        return $hasChanged ? $node : null;
    }

    public function configure(array $configuration): void
    {
        $allowedClasses = $configuration[self::ALLOWED_CLASSES] ?? [];
        Assert::isArray($allowedClasses);
        Assert::allString($allowedClasses);

        $this->allowedClasses = $allowedClasses;
    }

    private function shouldSkip(Class_ $classNode): bool
    {
        if ($classNode->isAbstract()) {
            return true;
        }

        $className = $this->getName($classNode);

        // Handle anonymous classes: they don't have "parents" in the same way for reflection
        // and can only have their own constructor.
        if ($className === null) {
            return $classNode->getMethod('__construct') instanceof ClassMethod;
        }

        // Check if the class is known to PHPStan's reflection
        // If the class is not found by reflection (e.g., dynamic, eval'd code),
        // it's safer to skip to avoid errors.
        // Alternatively, you could fallback to checking only the AST node:
        // return $classNode->getMethod('__construct') !== null;
        return ! $this->reflectionProvider->hasClass($className);
    }
}

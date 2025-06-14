<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\CodeQuality\General;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\ObjectType;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\NodeManipulator\ClassDependencyManipulator;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\PostRector\ValueObject\PropertyMetadata;
use Rector\Rector\AbstractRector;
use Rector\ValueObject\PhpVersionFeature;
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

        foreach ($node->getMethods() as $classMethod) {
            // If the method is static, we cannot perform DI with `$this`, so we skip this method entirely.
            if ($classMethod->isStatic()) {
                continue;
            }

            if ($classMethod->stmts === null) {
                continue;
            }

            if ($classMethod->stmts === []) {
                continue;
            }

            $this->traverseNodesWithCallable($classMethod->stmts, function (Node $subNode) use ($node, &$hasChanged) {
                if (! $subNode instanceof StaticCall) {
                    return null;
                }

                if (! $this->isName($subNode->name, 'makeInstance')) {
                    return null;
                }

                if (! $this->isObjectType($subNode->class, new ObjectType('TYPO3\CMS\Core\Utility\GeneralUtility'))) {
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

                if (\PHP_VERSION_ID >= PhpVersionFeature::READONLY_PROPERTY) {
                    $flags = Class_::MODIFIER_PRIVATE & Class_::MODIFIER_READONLY;
                } else {
                    $flags = Class_::MODIFIER_PRIVATE;
                }

                $propertyMetadata = new PropertyMetadata($propertyName, new ObjectType($className), $flags);

                $this->classDependencyManipulator->addConstructorDependency($node, $propertyMetadata);

                $hasChanged = true;

                return $this->nodeFactory->createPropertyFetch('this', $propertyName);
            });
        }

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
        if ($classNode->isAbstract() || $classNode->isAnonymous()) {
            return true;
        }

        $className = $this->getName($classNode);
        if ($className === null) {
            return true;
        }

        // Check if the class is known to PHPStan's reflection
        if (! $this->reflectionProvider->hasClass($className)) {
            // If class is not known to reflection, better to skip it.
            return true;
        }

        $classReflection = $this->reflectionProvider->getClass($className);

        // Traverse the class hierarchy (current class and its parents)
        $currentClassReflection = $classReflection;
        do {
            // Check if the current class in the hierarchy has its OWN constructor
            if ($currentClassReflection->hasNativeMethod('__construct')) {
                $constructorReflection = $currentClassReflection->getNativeMethod('__construct');

                // A constructor can have multiple variants (e.g. from phpdoc). We check the first one.
                $parametersAcceptor = $constructorReflection->getVariants()[0] ?? null;
                if ($parametersAcceptor === null) {
                    continue;
                }

                foreach ($parametersAcceptor->getParameters() as $parameterReflection) {
                    $paramType = $parameterReflection->getType();
                    if ($paramType->isScalar()->yes() || $paramType->isArray()->yes()) {
                        return true;
                    }
                }
            }

            $currentClassReflection = $currentClassReflection->getParentClass();
        } while ($currentClassReflection instanceof ClassReflection);

        // No constructor found in the current class or any of its parents
        return false;
    }
}

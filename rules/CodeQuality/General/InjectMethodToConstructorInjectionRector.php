<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\CodeQuality\General;

use PhpParser\Modifiers;
use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Function_;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\ObjectType;
use Rector\NodeManipulator\ClassDependencyManipulator;
use Rector\PostRector\ValueObject\PropertyMetadata;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/m/typo3/reference-coreapi/main/en-us/ApiOverview/DependencyInjection/Index.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\InjectMethodToConstructorInjectionRector\InjectMethodToConstructorInjectionRectorTest
 */
final class InjectMethodToConstructorInjectionRector extends AbstractRector implements DocumentedRuleInterface
{
    /**
     * @readonly
     */
    private ClassDependencyManipulator $classDependencyManipulator;

    /**
     * @readonly
     */
    private ReflectionProvider $reflectionProvider;

    public function __construct(
        ClassDependencyManipulator $classDependencyManipulator,
        ReflectionProvider $reflectionProvider
    ) {
        $this->classDependencyManipulator = $classDependencyManipulator;
        $this->reflectionProvider = $reflectionProvider;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace inject method to constructor injection',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Cache\CacheManager;

class Service
{
    private CacheManager $cacheManager;

    public function injectCacheManager(CacheManager $cacheManager): void
    {
        $this->cacheManager = $cacheManager;
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Cache\CacheManager;

class Service
{
    private CacheManager $cacheManager;

    public function __construct(CacheManager $cacheManager)
    {
        $this->cacheManager = $cacheManager;
    }
}
CODE_SAMPLE
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

        $injectMethods = array_filter(
            $node->getMethods(),
            static fn ($classMethod) => str_starts_with((string) $classMethod->name, 'inject')
        );
        if ($injectMethods === []) {
            return null;
        }

        foreach ($injectMethods as $injectMethod) {
            $params = $injectMethod->getParams();
            if ($params === []) {
                continue;
            }

            /** @var Param $param */
            $param = current($params);
            if (! $param->type instanceof FullyQualified) {
                continue;
            }

            $paramName = $this->getName($param->var);
            if ($paramName === null) {
                continue;
            }

            $propertyName = $paramName;

            // Try to determine property name from assignment in inject method
            if (isset($injectMethod->stmts[0]) && $injectMethod->stmts[0] instanceof Expression) {
                $statement = $injectMethod->stmts[0];
                $assign = $statement->expr;
                if ($assign instanceof Assign && $assign->var instanceof PropertyFetch) {
                    $propertyFetchName = $this->getName($assign->var->name);
                    if ($propertyFetchName !== null) {
                        $propertyName = $propertyFetchName;
                    }
                }
            }

            $this->classDependencyManipulator->addConstructorDependency(
                $node,
                new PropertyMetadata($propertyName, new ObjectType((string) $param->type), Modifiers::PRIVATE)
            );
            $this->removeNodeFromStatements($node, $injectMethod);
        }

        return $node;
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
        if (! $this->reflectionProvider->hasClass($className)) {
            // If the class is not found by reflection (e.g., dynamic, eval'd code),
            // it's safer to skip to avoid errors.
            // Alternatively, you could fallback to checking only the AST node:
            // return $classNode->getMethod('__construct') !== null;
            return true;
        }

        $classReflection = $this->reflectionProvider->getClass($className);

        // Traverse the class hierarchy (current class and its parents)
        $currentClassReflection = $classReflection;
        do {
            // Check if the current class in the hierarchy has its OWN constructor
            if ($currentClassReflection->hasNativeMethod('__construct')) {
                return true; // A constructor is defined in this class or an ancestor
            }

            $currentClassReflection = $currentClassReflection->getParentClass();
        } while ($currentClassReflection instanceof ClassReflection);

        // No constructor found in the current class or any of its parents
        return false;
    }

    /**
     * @param Class_ | ClassMethod | Function_ $nodeWithStatements
     */
    private function removeNodeFromStatements(Node $nodeWithStatements, Node $toBeRemovedNode): void
    {
        foreach ((array) $nodeWithStatements->stmts as $key => $stmt) {
            if ($toBeRemovedNode !== $stmt) {
                continue;
            }

            unset($nodeWithStatements->stmts[$key]);
            break;
        }
    }
}

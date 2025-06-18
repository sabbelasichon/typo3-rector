<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\CodeQuality\General;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Function_;
use PHPStan\Type\ObjectType;
use Rector\NodeManipulator\ClassDependencyManipulator;
use Rector\PostRector\ValueObject\PropertyMetadata;
use Rector\Rector\AbstractRector;
use Rector\ValueObject\PhpVersionFeature;
use Ssch\TYPO3Rector\RuleDefinition\RuleDefinitions;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/m/typo3/reference-coreapi/main/en-us/ApiOverview/DependencyInjection/Index.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\InjectMethodToConstructorInjectionRector\InjectMethodToConstructorInjectionRectorTest
 */
final class InjectMethodToConstructorInjectionRector extends AbstractRector
{
    /**
     * @readonly
     */
    private ClassDependencyManipulator $classDependencyManipulator;

    public function __construct(ClassDependencyManipulator $classDependencyManipulator)
    {
        $this->classDependencyManipulator = $classDependencyManipulator;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace inject method to constructor injection' . RuleDefinitions::COMPOSER_PATCH,
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
namespace App\Service;

use \TYPO3\CMS\Core\Cache\CacheManager;

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
namespace App\Service;

use \TYPO3\CMS\Core\Cache\CacheManager;

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

            if (isset($injectMethod->stmts[0]) && $injectMethod->stmts[0] instanceof Expression) {
                // check for the property name and if they match
                $statement = $injectMethod->stmts[0];

                $assign = $statement->expr;
                if (! $assign instanceof Assign) {
                    continue;
                }

                $propertyFetch = $assign->var;
                if (! $propertyFetch instanceof PropertyFetch) {
                    continue;
                }

                $paramName = $this->getName($propertyFetch->name);
                if ($paramName === null) {
                    continue;
                }
            }

            if (\PHP_VERSION_ID >= PhpVersionFeature::READONLY_PROPERTY) {
                $flags = Class_::MODIFIER_PRIVATE & Class_::MODIFIER_READONLY;
            } else {
                $flags = Class_::MODIFIER_PRIVATE;
            }

            $this->classDependencyManipulator->addConstructorDependency(
                $node,
                new PropertyMetadata($paramName, new ObjectType((string) $param->type), $flags)
            );
            $this->removeNodeFromStatements($node, $injectMethod);
        }

        return $node;
    }

    private function shouldSkip(Class_ $class): bool
    {
        return $class->getMethods() === [];
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

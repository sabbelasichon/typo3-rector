<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO314\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/14.0/Breaking-107315-CacheBackendAndFrontendRelatedChanges.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v14\v0\IntroduceStrictTypingForCacheBeAndFeRector\IntroduceStrictTypingForCacheBeAndFeRectorTest
 */
final class IntroduceStrictTypingForCacheBeAndFeRector extends AbstractRector implements DocumentedRuleInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Introduce strict typing for Cache BE and FE', [new CodeSample(
            <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Cache\Backend\BackendInterface;

class MyCustomBackend implements BackendInterface
{
    public function __construct($context, array $options = [])
    {
        parent::__construct($context, $options);
    }

    public function get($entryIdentifier)
    {
    }

    public function has($entryIdentifier)
    {
    }
}
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Cache\Backend\BackendInterface;

class MyCustomBackend implements BackendInterface
{
    public function __construct(array $options = [])
    {
        parent::__construct($options);
    }

    public function get(string $entryIdentifier): mixed
    {
    }

    public function has(string $entryIdentifier): bool
    {
    }
}
CODE_SAMPLE
        )]);
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
        if (! $this->isObjectType($node, new ObjectType('TYPO3\CMS\Core\Cache\Backend\AbstractBackend'))) {
            return null;
        }

        $constructorMethod = $node->getMethod('__construct');
        if (! $constructorMethod instanceof ClassMethod) {
            return null;
        }

        $parametersCount = count($constructorMethod->params);
        if ($parametersCount < 2) {
            return null;
        }

        if (isset($constructorMethod->params[0]) && $constructorMethod->params[0]->type !== null) {
            return null;
        }

        $this->processConstructorParameters($constructorMethod);
        $this->processParentCall($constructorMethod);

        return $node;
    }

    private function processConstructorParameters(ClassMethod $classMethod): void
    {
        unset($classMethod->params[0]);
        // Re-index the parameters array after removing the first element
        $classMethod->params = array_values($classMethod->params);
    }

    private function processParentCall(ClassMethod $classMethod): void
    {
        if ($classMethod->stmts === null) {
            return;
        }

        foreach ($classMethod->stmts as $stmt) {
            if (! $stmt instanceof Expression) {
                continue;
            }

            $expression = $stmt->expr;
            if (! $expression instanceof StaticCall) {
                continue;
            }

            // Check if it is a call to parent::__construct
            if (! $this->isName($expression->class, 'parent')) {
                continue;
            }

            if (! $this->isName($expression->name, '__construct')) {
                continue;
            }

            // The AbstractBackend constructor had 2 arguments: $context, $options
            // We now remove the first argument ($context) from the parent call.
            if (! isset($expression->args[0])) {
                continue;
            }

            unset($expression->args[0]);

            // Re-index the arguments array after removing the first element
            $expression->args = array_values($expression->args);

            break;
        }
    }
}

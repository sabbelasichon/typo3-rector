<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v12\v0\typo3;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Deprecation-96972-DeprecateQueryBuilderexecute.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\MigrateQueryBuilderExecuteRector\MigrateQueryBuilderExecuteRectorTest
 */
final class MigrateQueryBuilderExecuteRector extends AbstractRector
{
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
        if ($this->shouldSkip($node)) {
            return null;
        }

        if (! $this->isName($node->name, 'execute')) {
            return null;
        }

        $executeQueryMethods = ['select', 'addSelect', 'selectLiteral', 'addSelectLiteral', 'count'];

        $executeStatementMethods = ['insert', 'update', 'delete'];

        if ($this->checkSpecifiedMethodCalls($node, $executeQueryMethods)) {
            return $this->nodeFactory->createMethodCall($node->var, 'executeQuery', $node->args);
        }

        if ($this->checkSpecifiedMethodCalls($node, $executeStatementMethods)) {
            return $this->nodeFactory->createMethodCall($node->var, 'executeStatement', $node->args);
        }

        return null;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Replace Querybuilder::execute() with fitting methods', [new CodeSample(
            <<<'CODE_SAMPLE'
$rows = $queryBuilder
  ->select(...)
  ->from(...)
  ->execute()
  ->fetchAllAssociative();
$deletedRows = $queryBuilder
  ->delete(...)
  ->execute();
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
$rows = $queryBuilder
  ->select(...)
  ->from(...)
  ->executeQuery()
  ->fetchAllAssociative();
$deletedRows = $queryBuilder
  ->delete(...)
  ->executeStatement();
CODE_SAMPLE
        )]);
    }

    private function shouldSkip(MethodCall $methodCall): bool
    {
        return ! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $methodCall,
            new ObjectType('TYPO3\CMS\Core\Database\Query\QueryBuilder')
        );
    }

    private function checkSpecifiedMethodCalls(MethodCall $methodCall, array $methodsToCheckFor): bool
    {
        return (bool) $this->betterNodeFinder->findFirst($methodCall->var, function (Node $node) use (
            $methodsToCheckFor
        ): bool {
            if (! $node instanceof MethodCall) {
                return false;
            }

            if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
                $node,
                new ObjectType('TYPO3\CMS\Core\Database\Query\QueryBuilder')
            )) {
                return false;
            }

            return $this->isNames($node->name, $methodsToCheckFor);
        });
    }
}

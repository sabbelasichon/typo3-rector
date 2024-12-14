<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO312\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Breaking-96287-DoctrineDBALv3.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v0\MigrateFetchColumnToFetchOneRector\MigrateFetchColumnToFetchOneRectorTest
 * @see MigrateQueryBuilderExecuteRector
 */
final class MigrateFetchColumnToFetchOneRector extends AbstractRector implements DocumentedRuleInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate ->fetchColumn(0) to ->fetchOne()', [new CodeSample(
            <<<'CODE_SAMPLE'
$result = $queryBuilder
  ->select(...)
  ->from(...)
  ->executeQuery()
  ->fetchColumn(0);
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
$result = $queryBuilder
  ->select(...)
  ->from(...)
  ->executeQuery()
  ->fetchOne(0);
CODE_SAMPLE
        )]);
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
        if ($this->shouldSkip($node)) {
            return null;
        }

        return $this->nodeFactory->createMethodCall($node->var, 'fetchOne', $node->args);
    }

    private function shouldSkip(MethodCall $node): bool
    {
        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $node,
            new ObjectType('Doctrine\\DBAL\\Result')
        )) {
            return true;
        }

        return ! $this->nodeNameResolver->isName($node->name, 'fetchColumn');
    }
}

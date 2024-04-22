<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO312\v0;

use InvalidArgumentException;
use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Type\ObjectType;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Breaking-96287-DoctrineDBALv3.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v0\MigrateFetchAllToFetchAllAssociativeRector\MigrateFetchAllToFetchAllAssociativeRectorTest
 * @see MigrateQueryBuilderExecuteRector
 */
final class MigrateFetchAllToFetchAllAssociativeRector extends AbstractRector
{
    private ValueResolver $valueResolver;

    public function __construct(ValueResolver $valueResolver)
    {
        $this->valueResolver = $valueResolver;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate ->fetchAll() to ->fetchAllAssociative()', [new CodeSample(
            <<<'CODE_SAMPLE'
$result = $queryBuilder
  ->select(...)
  ->from(...)
  ->executeQuery()
  ->fetchAll();
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
$result = $queryBuilder
  ->select(...)
  ->from(...)
  ->executeQuery()
  ->fetchAllAssociative();
CODE_SAMPLE
        ), new CodeSample(
            <<<'CODE_SAMPLE'
$result = $queryBuilder
  ->select(...)
  ->from(...)
  ->executeQuery()
  ->fetchAll(FetchMode::NUMERIC);
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
$result = $queryBuilder
  ->select(...)
  ->from(...)
  ->executeQuery()
  ->fetchAllNumeric();
CODE_SAMPLE
        ), new CodeSample(
            <<<'CODE_SAMPLE'
$result = $queryBuilder
  ->select(...)
  ->from(...)
  ->executeQuery()
  ->fetchAll(FetchMode::COLUMN);
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
$result = $queryBuilder
  ->select(...)
  ->from(...)
  ->executeQuery()
  ->fetchFirstColumn();
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

        if ($node->args === []) {
            return $this->nodeFactory->createMethodCall($node->var, 'fetchAllAssociative');
        }

        /** @var Arg $argument */
        $argument = $node->args[0];
        $mode = $argument->value;

        $modeValue = $this->valueResolver->getValue($mode);

        switch ($modeValue) {
            case 'Doctrine\DBAL\FetchMode::ASSOCIATIVE':
            case 2:
                $method = 'fetchAllAssociative';
                break;
            case 'Doctrine\DBAL\FetchMode::NUMERIC':
            case 3:
                $method = 'fetchAllNumeric';
                break;
            case 'Doctrine\DBAL\FetchMode::COLUMN':
            case 7:
                $method = 'fetchFirstColumn';
                break;
            default:
                throw new InvalidArgumentException('Invalid mode: ' . $modeValue);
        }

        return $this->nodeFactory->createMethodCall($node->var, $method);
    }

    private function shouldSkip(MethodCall $node): bool
    {
        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $node,
            new ObjectType('Doctrine\\DBAL\\Result')
        )) {
            return true;
        }

        return ! $this->nodeNameResolver->isName($node->name, 'fetchAll');
    }
}

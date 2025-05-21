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
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Breaking-96287-DoctrineDBALv3.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v0\MigrateFetchToFetchAssociativeRector\MigrateFetchToFetchAssociativeRectorTest
 * @see MigrateQueryBuilderExecuteRector
 */
final class MigrateFetchToFetchAssociativeRector extends AbstractRector implements DocumentedRuleInterface
{
    private ValueResolver $valueResolver;

    public function __construct(ValueResolver $valueResolver)
    {
        $this->valueResolver = $valueResolver;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate `->fetch()` to `->fetchAssociative()`', [new CodeSample(
            <<<'CODE_SAMPLE'
$result = $queryBuilder
  ->select(...)
  ->from(...)
  ->executeQuery()
  ->fetch();
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
$result = $queryBuilder
  ->select(...)
  ->from(...)
  ->executeQuery()
  ->fetchAssociative();
CODE_SAMPLE
        ), new CodeSample(
            <<<'CODE_SAMPLE'
$result = $queryBuilder
  ->select(...)
  ->from(...)
  ->executeQuery()
  ->fetch(FetchMode::NUMERIC);
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
$result = $queryBuilder
  ->select(...)
  ->from(...)
  ->executeQuery()
  ->fetchNumeric();
CODE_SAMPLE
        ), new CodeSample(
            <<<'CODE_SAMPLE'
$result = $queryBuilder
  ->select(...)
  ->from(...)
  ->executeQuery()
  ->fetch(FetchMode::COLUMN);
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
$result = $queryBuilder
  ->select(...)
  ->from(...)
  ->executeQuery()
  ->fetchOne();
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
            return $this->nodeFactory->createMethodCall($node->var, 'fetchAssociative');
        }

        /** @var Arg $argument */
        $argument = $node->args[0];
        $mode = $argument->value;

        $modeValue = $this->valueResolver->getValue($mode);

        switch ($modeValue) {
            case 'Doctrine\DBAL\FetchMode::ASSOCIATIVE':
            case 2:
                $method = 'fetchAssociative';
                break;
            case 'Doctrine\DBAL\FetchMode::NUMERIC':
            case 3:
                $method = 'fetchNumeric';
                break;
            case 'Doctrine\DBAL\FetchMode::COLUMN':
            case 7:
                $method = 'fetchOne';
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

        return ! $this->isName($node->name, 'fetch');
    }
}

<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v12\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Deprecation-97354-ExpressionBuilderMethodsAndXAndOrX.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\ReplaceExpressionBuilderMethodsRector\ReplaceExpressionBuilderMethodsRectorTest
 */
final class ReplaceExpressionBuilderMethodsRector extends AbstractRector
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

        if (! $this->isNames($node->name, ['andX', 'orX'])) {
            return null;
        }

        $methodName = $this->isName($node->name, 'andX') ? 'and' : 'or';

        return $this->nodeFactory->createMethodCall($node->var, $methodName, $node->args);
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Replaces ExpressionBuilder methods orX() & andX()', [new CodeSample(
            <<<'CODE_SAMPLE'
$rows = $queryBuilder
  ->select(...)
  ->from(...)
  ->where(
    $queryBuilder->expr()->andX(...),
    $queryBuilder->expr()->orX(...)
  )
  ->executeQuery()
  ->fetchAllAssociative();
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
$rows = $queryBuilder
  ->select(...)
  ->from(...)
  ->where(
    $queryBuilder->expr()->and(...),
    $queryBuilder->expr()->or(...)
  )
  ->executeQuery()
  ->fetchAllAssociative();
CODE_SAMPLE
        )]);
    }

    private function shouldSkip(MethodCall $methodCall): bool
    {
        return ! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $methodCall,
            new ObjectType('TYPO3\CMS\Core\Database\Query\Expression\ExpressionBuilder')
        );
    }
}

<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\CodeQuality\General;

use PhpParser\Node;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\Throw_ as ThrowExpression;
use PhpParser\Node\Stmt\Throw_ as ThrowStatement;
use Rector\Rector\AbstractRector;
use Rector\Testing\PHPUnit\StaticPHPUnitEnvironment;
use Ssch\TYPO3Rector\Contract\NoChangelogRequiredInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\AddErrorCodeToExceptionRector\AddErrorCodeToExceptionRectorTest
 */
final class AddErrorCodeToExceptionRector extends AbstractRector implements NoChangelogRequiredInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Add timestamp error code to exceptions', [new CodeSample(
            <<<'CODE_SAMPLE'
throw new \RuntimeException('my message');
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
throw new \RuntimeException('my message', 1729021897);
CODE_SAMPLE
        )]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [ThrowStatement::class, ThrowExpression::class];
    }

    /**
     * @param ThrowStatement|ThrowExpression $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($this->shouldSkip($node)) {
            return null;
        }

        // generate a random 10-digit number as timestamp
        $timestamp = random_int(1000000000, 9999999999);
        if (StaticPHPUnitEnvironment::isPHPUnitRun()) {
            $timestamp = 1729021897;
        }

        $arg = $this->nodeFactory->createArg($timestamp);

        /** @var New_ $newExpression */
        $newExpression = $node->expr;
        $newExpression->args[] = $arg;
        return $node;
    }

    /**
     * @param ThrowStatement|ThrowExpression $node
     */
    private function shouldSkip($node): bool
    {
        if (! $node->expr instanceof New_) {
            return true;
        }

        return count($node->expr->args) !== 1;
    }
}

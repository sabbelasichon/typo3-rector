<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO312\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Scalar\String_;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Deprecation-97244-DirectInstantiationOfCompositeExpression.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\UseCompositeExpressionStaticMethodsRector\UseCompositeExpressionStaticMethodsRectorTest
 */
final class UseCompositeExpressionStaticMethodsRector extends AbstractRector
{
    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [New_::class];
    }

    /**
     * @param New_ $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($this->shouldSkip($node)) {
            return null;
        }

        if ($node->args[0]->value->getType() === 'Expr_ClassConstFetch') {
            /** @var ClassConstFetch $firstArg */
            $firstArg = $node->args[0]->value;
            /** @var Node\Identifier $identifier */
            $identifier = $firstArg->name;
            $methodType = $identifier->name === 'TYPE_AND' ? 'and' : 'or';
        } else {
            /** @var String_ $firstArg */
            $firstArg = $node->args[0]->value;
            $methodType = strtolower($firstArg->value);
        }

        return $this->nodeFactory->createStaticCall(
            'TYPO3\CMS\Core\Database\Query\Expression\CompositeExpression',
            $methodType,
            [$node->args[1]]
        );
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Use CompositeExpression static methods instead of constructor', [new CodeSample(
            <<<'CODE_SAMPLE'
$compositeExpressionAND = new CompositeExpression(CompositeExpression::TYPE_AND, []);
$compositeExpressionOR = new CompositeExpression(CompositeExpression::TYPE_OR, []);
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
$compositeExpressionAND = CompositeExpression::and([]);
$compositeExpressionOR = CompositeExpression::or([]);
CODE_SAMPLE
        )]);
    }

    private function shouldSkip(New_ $method): bool
    {
        return ! $this->isObjectType(
            $method,
            new ObjectType('TYPO3\CMS\Core\Database\Query\Expression\CompositeExpression')
        );
    }
}

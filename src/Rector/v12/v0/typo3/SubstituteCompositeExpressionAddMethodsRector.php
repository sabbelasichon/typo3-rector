<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v12\v0\typo3;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Deprecation-97244-CompositeExpressionMethodsAddAndAddMultiple.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\SubstituteCompositeExpressionAddMethodsRector\SubstituteCompositeExpressionAddMethodsRectorTest
 */
final class SubstituteCompositeExpressionAddMethodsRector extends AbstractRector
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

        if (! $this->isNames($node->name, ['add', 'addMultiple'])) {
            return null;
        }

        if ($this->isName($node->name, 'addMultiple')) {
            /** @var Arg $arg */
            $arg = $node->getArgs()[0];
            $argValue = $arg->value;
            $arguments = [new Arg($argValue, false, true)];
        } else {
            $arguments = $node->args;
        }

        return new Assign($node->var, $this->nodeFactory->createMethodCall($node->var, 'with', $arguments));
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Replace add() and addMultiple() of CompositeExpression with with()', [new CodeSample(
            <<<'CODE_SAMPLE'
$compositeExpression = CompositeExpression::or();

$compositeExpression->add(
    $queryBuilder->expr()->eq(
        'field',
        $queryBuilder->createNamedParameter('foo')
    )
);

$compositeExpression->addMultiple(
    [
        $queryBuilder->expr()->eq(
            'field',
            $queryBuilder->createNamedParameter('bar')
        ),
        $queryBuilder->expr()->eq(
            'field',
            $queryBuilder->createNamedParameter('baz')
        ),
    ]
);
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
$compositeExpression = CompositeExpression::or();

$compositeExpression = $compositeExpression->with(
    $queryBuilder->expr()->eq(
        'field',
        $queryBuilder->createNamedParameter('foo')
    )
);

$compositeExpression = $compositeExpression->with(
    ...[
        $queryBuilder->expr()->eq(
            'field',
            $queryBuilder->createNamedParameter('bar')
        ),
        $queryBuilder->expr()->eq(
            'field',
            $queryBuilder->createNamedParameter('baz')
        ),
    ]
);
CODE_SAMPLE
        )]);
    }

    private function shouldSkip(MethodCall $method): bool
    {
        return ! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $method,
            new ObjectType('TYPO3\CMS\Core\Database\Query\Expression\CompositeExpression')
        );
    }
}

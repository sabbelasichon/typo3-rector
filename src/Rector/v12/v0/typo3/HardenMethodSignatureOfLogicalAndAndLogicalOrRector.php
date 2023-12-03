<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v12\v0\typo3;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\BinaryOp;
use PhpParser\Node\Expr\BinaryOp\GreaterOrEqual;
use PhpParser\Node\Expr\BinaryOp\Identical;
use PhpParser\Node\Expr\BooleanNot;
use PhpParser\Node\Expr\Empty_;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\ElseIf_;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\If_;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Rector\PostRector\Collector\NodesToAddCollector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Breaking-96044-HardenMethodSignatureOfLogicalAndAndLogicalOr.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\HardenMethodSignatureOfLogicalAndAndLogicalOrRector\HardenMethodSignatureOfLogicalAndAndLogicalOrRectorTest
 */
final class HardenMethodSignatureOfLogicalAndAndLogicalOrRector extends AbstractRector
{
    /**
     * @readonly
     */
    public NodesToAddCollector $nodesToAddCollector;

    public function __construct(NodesToAddCollector $nodesToAddCollector)
    {
        $this->nodesToAddCollector = $nodesToAddCollector;
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
        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $node,
            new ObjectType('TYPO3\CMS\Extbase\Persistence\QueryInterface')
        )) {
            return null;
        }

        if (! $this->isNames($node->name, ['logicalAnd', 'logicalOr'])) {
            return null;
        }

        $args = $node->getArgs();
        if (! isset($args[0])) {
            return null;
        }

        if (count($args) > 1) {
            return null;
        }

        if ($args[0]->unpack) {
            // In this case, the code looks like this: $query->logicalAnd(...$constraints);
            $parentIfStatement = $this->betterNodeFinder->findParentType($node, If_::class);
            // the if should not contain a count, otherwise it is probably migrated already
            if ($parentIfStatement instanceof If_ && $parentIfStatement->cond instanceof Identical) {
                $comparison = $parentIfStatement->cond;
                if ($comparison->left instanceof FuncCall && $this->isName($comparison->left, 'count')) {
                    return null;
                }

                if ($comparison->right instanceof FuncCall && $this->isName($comparison->right, 'count')) {
                    return null;
                }
            }
        }

        $firstArgument = $args[0]->value;

        if ($firstArgument instanceof Variable) {
            // In this case, the code looks like this: $query->logicalAnd($constraints);
            return $this->handleArgumentIsVariable($node, $firstArgument);
        }

        if (! ($firstArgument instanceof Array_)) {
            return null;
        }

        // In this case, the code looks like this: $query->logicalAnd([...])

        // Maybe add "new \PhpParser\Node\Stmt\Nop()" somehow for long lines?
        $node->args = $this->nodeFactory->createArgs([...$firstArgument->items]);

        return $node;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Use multiple parameters instead of array for logicalOr and logicalAnd of Extbase Query class',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
use TYPO3\CMS\Extbase\Persistence\Repository;

class ProductRepositoryLogicalAnd extends Repository
{
    public function findAllForList()
    {
        $query = $this->createQuery();
        $query->matching($query->logicalAnd([
            $query->equals('propertyName1', 'value1'),
            $query->equals('propertyName2', 'value2'),
            $query->equals('propertyName3', 'value3'),
        ]));
    }
    public function findAllForSomething()
    {
        $query = $this->createQuery();
        $constraints[] = $query->lessThan('foo', 1);
        $constraints[] = $query->lessThan('bar', 1);
        $query->matching($query->logicalAnd($constraints));
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
use TYPO3\CMS\Extbase\Persistence\Repository;

class ProductRepositoryLogicalAnd extends Repository
{
    public function findAllForList()
    {
        $query = $this->createQuery();
        $query->matching($query->logicalAnd(
            $query->equals('propertyName1', 'value1'),
            $query->equals('propertyName2', 'value2'),
            $query->equals('propertyName3', 'value3'),
        ));
    }
    public function findAllForSomething()
    {
        $query = $this->createQuery();
        $constraints[] = $query->lessThan('foo', 1);
        $constraints[] = $query->lessThan('bar', 1);
        $query->matching($query->logicalAnd(...$constraints));
    }
}
CODE_SAMPLE
                ),
            ]
        );
    }

    private function handleArgumentIsVariable(MethodCall $node, Variable $firstArgument): ?MethodCall
    {
        $parentIfStatement = $this->betterNodeFinder->findParentType($node, If_::class);

        $currentStmt = $this->betterNodeFinder->resolveCurrentStatement($node);
        if (! $currentStmt instanceof Stmt) {
            return null;
        }

        $parentNode = $node->getAttribute('parent');

        $logicalAndOrOr = $this->isName($node->name, 'logicalAnd') ? 'logicalAnd' : 'logicalOr';

        $queryVariable = $node->var instanceof Variable ? $node->var : new Variable('query');

        if ($parentNode instanceof Assign && $parentNode->expr instanceof MethodCall) {
            // FIXME: This case is quite complicated as we don't really know how the code looks like. This is WIP for now...
            // This is how a real world example could look like. See: https://review.typo3.org/c/Packages/TYPO3.CMS/+/72244/8/typo3/sysext/beuser/Classes/Domain/Repository/BackendUserRepository.php
            // $constraints = [];
            // $query = $this->createQuery();
            // $query->setOrderings(['userName' => QueryInterface::ORDER_ASCENDING]);
            // // Username
            // if ($demand->getUserName() !== '') {
            //     $searchConstraints = [];
            //     $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('be_users');
            //     foreach (['userName', 'realName'] as $field) {
            //         $searchConstraints[] = $query->like(
            //             $field,
            //             '%' . $queryBuilder->escapeLikeWildcards($demand->getUserName()) . '%'
            //         );
            //     }
            //     if (MathUtility::canBeInterpretedAsInteger($demand->getUserName())) {
            //         $searchConstraints[] = $query->equals('uid', (int)$demand->getUserName());
            //     }
            //     $constraints[] = $query->logicalOr($searchConstraints);
            // }
            $if = $this->createIfForAssignment($parentNode, $firstArgument);
        } else {
            $if = $this->createIfForNormalMethod($firstArgument, $queryVariable, $logicalAndOrOr);
        }

        if (! ($parentIfStatement instanceof If_)) {
            $this->nodesToAddCollector->addNodeBeforeNode($if, $node);
            return $node;
        }

        if ($this->isBinaryOpAndNameAppearsInConditions($parentIfStatement, $firstArgument)
            || $this->isBooleanNotAndNameAppears($parentIfStatement, $firstArgument)
            || $this->isEmptyAndNameAppears($parentIfStatement, $firstArgument)
            || $this->isVariableAndNameAppears($parentIfStatement, $firstArgument)
        ) {
            $this->removeNode($parentIfStatement);
            $this->nodesToAddCollector->addNodeBeforeNode($if, $parentIfStatement);
        } else {
            #$this->removeNode($node);
            $this->nodesToAddCollector->addNodeBeforeNode($if, $node);
        }

        return $node;
    }

    private function createIfForAssignment(Assign $parentNode, Variable $firstArgument): If_
    {
        $ifExpression = clone $parentNode;
        $ifExpression->expr = $this->nodeFactory->createFuncCall('reset', [$firstArgument]);

        return new If_(
            new Identical($this->nodeFactory->createFuncCall('count', [$firstArgument]), new LNumber(1)),
            [
                'stmts' => [$ifExpression],
                'elseifs' => [
                    new ElseIf_(
                        new GreaterOrEqual(
                            $this->nodeFactory->createFuncCall('count', [$firstArgument]),
                            new LNumber(2)
                        ),
                        [new Expression($parentNode->expr)]
                    ),
                ],
            ]
        );
    }

    private function createIfForNormalMethod(
        Variable $firstArgument,
        Variable $queryVariable,
        string $logicalAndOrOr
    ): If_ {
        return new If_(
            new Identical($this->nodeFactory->createFuncCall('count', [$firstArgument]), new LNumber(1)),
            [
                'stmts' => [
                    new Expression(
                        $this->nodeFactory->createMethodCall(
                            $queryVariable,
                            'matching',
                            [new Arg($this->nodeFactory->createFuncCall('reset', [$firstArgument]))]
                        )
                    ),
                ],
                'elseifs' => [
                    new ElseIf_(
                        new GreaterOrEqual(
                            $this->nodeFactory->createFuncCall('count', [$firstArgument]),
                            new LNumber(2)
                        ),
                        [
                            new Expression(
                                $this->nodeFactory->createMethodCall(
                                    $queryVariable,
                                    'matching',
                                    [
                                        new Arg(
                                            $this->nodeFactory->createMethodCall(
                                                $queryVariable,
                                                $logicalAndOrOr,
                                                [new Arg($firstArgument, false, true)]
                                            ),
                                        ),
                                    ]
                                )
                            ),
                        ]
                    ),
                ],
            ]
        );
    }

    private function isBinaryOpAndNameAppearsInConditions(If_ $parentIfStatement, Variable $firstArgument): bool
    {
        if (! ($parentIfStatement->cond instanceof BinaryOp)) {
            return false;
        }

        /** @var string $name */
        $name = $firstArgument->name;

        return ($parentIfStatement->cond->left instanceof Variable && $this->isName(
            $parentIfStatement->cond->left,
            $name
        ))
            || ($parentIfStatement->cond->right instanceof Variable && $this->isName(
                $parentIfStatement->cond->right,
                $name
            ));
    }

    private function isBooleanNotAndNameAppears(If_ $parentIfStatement, Variable $firstArgument): bool
    {
        if (! ($parentIfStatement->cond instanceof BooleanNot)) {
            return false;
        }

        /** @var string $name */
        $name = $firstArgument->name;

        if ($parentIfStatement->cond->expr instanceof Variable) {
            return $this->isName($parentIfStatement->cond->expr, $name);
        }

        if ($parentIfStatement->cond->expr instanceof Empty_) {
            return $this->isName($parentIfStatement->cond->expr->expr, $name);
        }

        return false;
    }

    private function isEmptyAndNameAppears(If_ $parentIfStatement, Variable $firstArgument): bool
    {
        if (! ($parentIfStatement->cond instanceof Empty_)) {
            return false;
        }

        /** @var string $name */
        $name = $firstArgument->name;

        return $this->isName($parentIfStatement->cond->expr, $name);
    }

    private function isVariableAndNameAppears(If_ $parentIfStatement, Variable $firstArgument): bool
    {
        if (! ($parentIfStatement->cond instanceof Variable)) {
            return false;
        }

        /** @var string $name */
        $name = $firstArgument->name;

        return $this->isName($parentIfStatement->cond, $name);
    }
}

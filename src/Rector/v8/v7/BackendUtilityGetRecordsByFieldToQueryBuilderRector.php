<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v8\v7;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\BinaryOp\NotIdentical;
use PhpParser\Node\Expr\Cast\Int_;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Else_;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Foreach_;
use PhpParser\Node\Stmt\If_;
use PhpParser\Node\Stmt\Return_;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/8.7/Deprecation-79122-DeprecateBackendUtilitygetRecordsByField.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v8\v7\BackendUtilityGetRecordsByFieldToQueryBuilderRector\BackendUtilityGetRecordsByFieldToQueryBuilderRectorTest
 */
final class BackendUtilityGetRecordsByFieldToQueryBuilderRector extends AbstractRector
{
    /**
     * @var string
     */
    private const MAKE_INSTANCE = 'makeInstance';

    /**
     * @var string
     */
    private const LIMIT_OFFSET_AND_MAX = 'limitOffsetAndMax';

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Expression::class, Return_::class];
    }

    /**
     * @param Expression|Return_ $node
     *
     * @return Node[]|null
     */
    public function refactor(Node $node): ?array
    {
        $methodCall = $this->betterNodeFinder->findFirstInstanceOf([$node], StaticCall::class);

        if (! $methodCall instanceof StaticCall) {
            return null;
        }

        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $methodCall,
            new ObjectType('TYPO3\CMS\Backend\Utility\BackendUtility')
        )) {
            return null;
        }

        if (! $this->isName($methodCall->name, 'getRecordsByField')) {
            return null;
        }

        $queryBuilderVariableName = $this->extractQueryBuilderVariableName($methodCall);

        $nodes = array_filter([
            $this->addQueryBuilderNode($methodCall),
            $this->addQueryBuilderBackendWorkspaceRestrictionNode($queryBuilderVariableName),
            $this->addQueryBuilderDeletedRestrictionNode($queryBuilderVariableName, $methodCall),
            $this->addQueryBuilderSelectNode($queryBuilderVariableName, $methodCall),
            $this->addQueryWhereNode($queryBuilderVariableName, $methodCall),
            $this->addQueryGroupByNode($queryBuilderVariableName, $methodCall),
            $this->addOrderByNode($queryBuilderVariableName, $methodCall),
            $this->addLimitNode($queryBuilderVariableName, $methodCall),
            $node,
        ]);

        $queryBuilderMethodCall = $this->nodeFactory->createMethodCall(
            $this->nodeFactory->createMethodCall($queryBuilderVariableName, 'execute'),
            'fetchAll'
        );

        if ($node instanceof Return_) {
            $node->expr = $queryBuilderMethodCall;
        } elseif ($node->expr instanceof Assign) {
            $node->expr->expr = $queryBuilderMethodCall;
        }

        return $nodes;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('BackendUtility::getRecordsByField to QueryBuilder', [
            new CodeSample(
                <<<'CODE_SAMPLE'
use TYPO3\CMS\Backend\Utility\BackendUtility;

$rows = BackendUtility::getRecordsByField('table', 'uid', 3);
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\BackendWorkspaceRestriction;
use TYPO3\CMS\Core\Database\Query\Restriction\DeletedRestriction;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('table');
$queryBuilder->getRestrictions()->removeAll()->add(GeneralUtility::makeInstance(BackendWorkspaceRestriction::class));
$queryBuilder->getRestrictions()->add(GeneralUtility::makeInstance(DeletedRestriction::class));
$queryBuilder->select('*')->from('table')->where($queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter(3)));
$rows = $queryBuilder->execute()->fetchAll();
CODE_SAMPLE
            ),
        ]);
    }

    private function addQueryBuilderNode(StaticCall $staticCall): ?Expression
    {
        $queryBuilderArgument = $staticCall->args[8] ?? null;
        if ($this->isVariable($queryBuilderArgument)) {
            return null;
        }

        $tableArgument = $staticCall->args[0];

        if (! $queryBuilderArgument instanceof Arg || $this->valueResolver->getValue(
            $queryBuilderArgument->value
        ) === 'null') {
            $table = $this->valueResolver->getValue($tableArgument->value);
            if ($table === null) {
                $table = $tableArgument;
            }

            $queryBuilder = $this->nodeFactory->createMethodCall(
                $this->nodeFactory->createStaticCall(
                    'TYPO3\CMS\Core\Utility\GeneralUtility',
                    self::MAKE_INSTANCE,
                    [$this->nodeFactory->createClassConstReference('TYPO3\CMS\Core\Database\ConnectionPool')]
                ),
                'getQueryBuilderForTable',
                [$table]
            );
        } else {
            $queryBuilder = $queryBuilderArgument->value;
        }

        return new Expression(new Assign(new Variable('queryBuilder'), $queryBuilder));
    }

    private function isVariable(?Arg $queryBuilderArgument): bool
    {
        return $queryBuilderArgument instanceof Arg && $queryBuilderArgument->value instanceof Variable;
    }

    private function extractQueryBuilderVariableName(StaticCall $staticCall): string
    {
        $queryBuilderArgument = $staticCall->getArgs()[8] ?? null;
        $queryBuilderVariableName = 'queryBuilder';
        if ($queryBuilderArgument instanceof Arg && $this->isVariable($queryBuilderArgument)) {
            $queryBuilderVariableName = $this->getName($queryBuilderArgument->value);
        }

        return (string) $queryBuilderVariableName;
    }

    private function addQueryBuilderBackendWorkspaceRestrictionNode(string $queryBuilderVariableName): Expression
    {
        return new Expression($this->nodeFactory->createMethodCall(
            $this->nodeFactory->createMethodCall(
                $this->nodeFactory->createMethodCall($queryBuilderVariableName, 'getRestrictions'),
                'removeAll'
            ),
            'add',
            [
                $this->nodeFactory->createStaticCall(
                    'TYPO3\CMS\Core\Utility\GeneralUtility',
                    self::MAKE_INSTANCE,
                    [
                        $this->nodeFactory->createClassConstReference(
                            'TYPO3\CMS\Core\Database\Query\Restriction\BackendWorkspaceRestriction'
                        ),
                    ]
                ),
            ]
        ));
    }

    private function addQueryBuilderDeletedRestrictionNode(
        string     $queryBuilderVariableName,
        StaticCall $node
    ): ?Node {
        $useDeleteClauseArgument = $node->args[7] ?? null;
        $useDeleteClause = $useDeleteClauseArgument !== null ? $this->valueResolver->getValue(
            $useDeleteClauseArgument->value
        ) : true;

        if ($useDeleteClause === false) {
            return null;
        }

        $deletedRestrictionNode = $this->nodeFactory->createMethodCall(
            $this->nodeFactory->createMethodCall($queryBuilderVariableName, 'getRestrictions'),
            'add',
            [
                $this->nodeFactory->createStaticCall(
                    'TYPO3\CMS\Core\Utility\GeneralUtility',
                    self::MAKE_INSTANCE,
                    [
                        $this->nodeFactory->createClassConstReference(
                            'TYPO3\CMS\Core\Database\Query\Restriction\DeletedRestriction'
                        ),
                    ]
                ),
            ]
        );

        if ($useDeleteClause) {
            return new Expression($deletedRestrictionNode);
        }

        if (! $useDeleteClauseArgument instanceof Arg) {
            return null;
        }

        $if = new If_($useDeleteClauseArgument->value);
        $if->stmts[] = new Expression($deletedRestrictionNode);

        return $if;
    }

    private function addQueryBuilderSelectNode(string $queryBuilderVariableName, StaticCall $node): Expression
    {
        $queryBuilderWhereExpressionNode = $this->nodeFactory->createMethodCall(
            $this->nodeFactory->createMethodCall($queryBuilderVariableName, 'expr'),
            'eq',
            [
                $node->args[1]->value,
                $this->nodeFactory->createMethodCall(
                    $queryBuilderVariableName,
                    'createNamedParameter',
                    [$node->args[2]->value]
                ),
            ]
        );
        return new Expression($this->nodeFactory->createMethodCall(
            $this->nodeFactory->createMethodCall(
                $this->nodeFactory->createMethodCall($queryBuilderVariableName, 'select', ['*']),
                'from',
                [$node->args[0]->value]
            ),
            'where',
            [$queryBuilderWhereExpressionNode]
        ));
    }

    private function addQueryWhereNode(string $queryBuilderVariableName, StaticCall $staticCall): ?Node
    {
        $whereClauseArgument = $staticCall->args[3] ?? null;
        $whereClause = $whereClauseArgument !== null ? $this->valueResolver->getValue($whereClauseArgument->value) : '';

        if ($whereClause === '') {
            return null;
        }

        $whereClauseNode = $this->nodeFactory->createMethodCall($queryBuilderVariableName, 'andWhere', [
            $this->nodeFactory->createStaticCall(
                'TYPO3\CMS\Core\Database\Query\QueryHelper',
                'stripLogicalOperatorPrefix',
                [$staticCall->args[3]]
            ),
        ]);

        if ($whereClause) {
            return new Expression($whereClauseNode);
        }

        if (! $whereClauseArgument instanceof Arg) {
            return null;
        }

        $if = new If_($whereClauseArgument->value);
        $if->stmts[] = new Expression($whereClauseNode);

        return $if;
    }

    private function addQueryGroupByNode(string $queryBuilderVariableName, StaticCall $staticCall): ?Node
    {
        $groupByArgument = $staticCall->args[4] ?? null;
        $groupBy = $groupByArgument !== null ? $this->valueResolver->getValue($groupByArgument->value) : '';

        if ($groupBy === '') {
            return null;
        }

        $groupByNode = $this->nodeFactory->createMethodCall($queryBuilderVariableName, 'groupBy', [
            $this->nodeFactory->createStaticCall(
                'TYPO3\CMS\Core\Database\Query\QueryHelper',
                'parseGroupBy',
                [$staticCall->args[4]]
            ),
        ]);

        if ($groupBy) {
            return new Expression($groupByNode);
        }

        if (! $groupByArgument instanceof Arg) {
            return null;
        }

        $if = new If_(new NotIdentical($groupByArgument->value, new String_('')));
        $if->stmts[] = new Expression($groupByNode);

        return $if;
    }

    private function addOrderByNode(string $queryBuilderVariableName, StaticCall $staticCall): ?Node
    {
        $orderByArgument = $staticCall->args[5] ?? null;
        $orderBy = $orderByArgument !== null ? $this->valueResolver->getValue($orderByArgument->value) : '';

        if ($orderBy === '' || $orderBy === 'null') {
            return null;
        }

        if (! $orderByArgument instanceof Arg) {
            return null;
        }

        $orderByForeach = new Foreach_(
            $this->nodeFactory->createStaticCall(
                'TYPO3\CMS\Core\Database\Query\QueryHelper',
                'parseOrderBy',
                [$orderByArgument->value]
            ),
            new Variable('orderPair')
        );
        $orderByForeach->stmts[] = new Expression(
            new Assign(
                $this->nodeFactory->createFuncCall('list', [new Variable('fieldName'), new Variable('order')]),
                new Variable('orderPair')
            )
        );
        $orderByForeach->stmts[] = new Expression($this->nodeFactory->createMethodCall(
            $queryBuilderVariableName,
            'addOrderBy',
            [new Variable('fieldName'), new Variable('order')]
        ));

        if ($orderBy) {
            return $orderByForeach;
        }

        $if = new If_(new NotIdentical($orderByArgument->value, new String_('')));
        $if->stmts[] = $orderByForeach;

        return $if;
    }

    private function addLimitNode(string $queryBuilderVariableName, StaticCall $staticCall): ?Node
    {
        $limitArgument = $staticCall->args[6] ?? null;
        $limit = $limitArgument !== null ? $this->valueResolver->getValue($limitArgument->value) : '';

        if ($limit === '') {
            return null;
        }

        if (! $limitArgument instanceof Arg) {
            return null;
        }

        $limitIf = new If_($this->nodeFactory->createFuncCall('strpos', [$limitArgument->value, ',']));
        $limitIf->stmts[] = new Expression(
            new Assign(
                new Variable(self::LIMIT_OFFSET_AND_MAX),
                $this->nodeFactory->createStaticCall(
                    'TYPO3\CMS\Core\Utility\GeneralUtility',
                    'intExplode',
                    [new String_(','), new Variable('limit')]
                )
            )
        );
        $limitIf->stmts[] = new Expression($this->nodeFactory->createMethodCall(
            $queryBuilderVariableName,
            'setFirstResult',
            [new Int_(new ArrayDimFetch(new Variable(self::LIMIT_OFFSET_AND_MAX), new LNumber(0)))]
        ));
        $limitIf->stmts[] = new Expression(
            $this->nodeFactory->createMethodCall($queryBuilderVariableName, 'setMaxResults', [
                new Int_(new ArrayDimFetch(new Variable(self::LIMIT_OFFSET_AND_MAX), new LNumber(1))),
            ])
        );

        $limitIf->else = new Else_();
        $limitIf->else->stmts[] = new Expression($this->nodeFactory->createMethodCall(
            $queryBuilderVariableName,
            'setMaxResults',
            [new Int_(new Variable('limit'))]
        ));

        if ($limit) {
            return $limitIf;
        }

        $if = new If_(new NotIdentical($limitArgument->value, new String_('')));
        $if->stmts[] = $limitIf;

        return $if;
    }
}

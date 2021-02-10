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
use Rector\Core\Rector\AbstractRector;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryHelper;
use TYPO3\CMS\Core\Database\Query\Restriction\BackendWorkspaceRestriction;
use TYPO3\CMS\Core\Database\Query\Restriction\DeletedRestriction;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/8.7/Deprecation-79122-DeprecateBackendUtilitygetRecordsByField.html
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

    public function getNodeTypes(): array
    {
        return [StaticCall::class];
    }

    /**
     * @param StaticCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType($node, BackendUtility::class)) {
            return null;
        }

        if (! $this->isName($node->name, 'getRecordsByField')) {
            return null;
        }

        $parentNode = $node->getAttribute(AttributeKey::PARENT_NODE);

        $positionNode = $node;
        if ($parentNode instanceof Return_) {
            $positionNode = $parentNode;
        }

        $this->addQueryBuilderNode($node, $positionNode);

        $queryBuilderVariableName = $this->extractQueryBuilderVariableName($node);

        $this->addQueryBuilderBackendWorkspaceRestrictionNode($queryBuilderVariableName, $positionNode);
        $this->addQueryBuilderDeletedRestrictionNode($queryBuilderVariableName, $node, $positionNode);
        $this->addQueryBuilderSelectNode($queryBuilderVariableName, $node, $positionNode);
        $this->addQueryWhereNode($queryBuilderVariableName, $node, $positionNode);
        $this->addQueryGroupByNode($queryBuilderVariableName, $node, $positionNode);
        $this->addOrderByNode($queryBuilderVariableName, $node, $positionNode);
        $this->addLimitNode($queryBuilderVariableName, $node, $positionNode);

        return $this->nodeFactory->createMethodCall(
            $this->nodeFactory->createMethodCall($queryBuilderVariableName, 'execute'),
            'fetchAll'
        );
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('BackendUtility::getRecordsByField to QueryBuilder', [
            new CodeSample(<<<'PHP'
use TYPO3\CMS\Backend\Utility\BackendUtility;
$rows = BackendUtility::getRecordsByField('table', 'uid', 3);
PHP
                , <<<'PHP'
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\BackendWorkspaceRestriction;
use TYPO3\CMS\Core\Database\Query\Restriction\DeletedRestriction;
$queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('table');
$queryBuilder->getRestrictions()->removeAll()->add(GeneralUtility::makeInstance(BackendWorkspaceRestriction::class));
$queryBuilder->getRestrictions()->add(GeneralUtility::makeInstance(DeletedRestriction::class));
$queryBuilder->select('*')->from('table')->where($queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter(3)));
$rows = $queryBuilder->execute()->fetchAll();
PHP
            ),
        ]);
    }

    private function addQueryBuilderNode(StaticCall $node, Node $positionNode): void
    {
        $queryBuilderArgument = $node->args[8] ?? null;
        if ($this->isVariable($queryBuilderArgument)) {
            return;
        }

        $tableArgument = $node->args[0];

        if (null === $queryBuilderArgument || 'null' === $this->valueResolver->getValue($queryBuilderArgument->value)) {
            $table = $this->valueResolver->getValue($tableArgument->value);
            if (null === $table) {
                $table = $tableArgument;
            }

            $queryBuilder = $this->nodeFactory->createMethodCall(
                $this->nodeFactory->createStaticCall(
                    GeneralUtility::class,
                    self::MAKE_INSTANCE,
                    [$this->nodeFactory->createClassConstReference(ConnectionPool::class)]
                ),
                'getQueryBuilderForTable',
                [$table]
            );
        } else {
            $queryBuilder = $queryBuilderArgument->value;
        }

        $queryBuilderNode = new Assign(new Variable('queryBuilder'), $queryBuilder);
        $this->addNodeBeforeNode($queryBuilderNode, $positionNode);
    }

    private function isVariable(?Arg $queryBuilderArgument): bool
    {
        return null !== $queryBuilderArgument && $queryBuilderArgument->value instanceof Variable;
    }

    private function extractQueryBuilderVariableName(StaticCall $node): string
    {
        $queryBuilderArgument = $node->args[8] ?? null;
        $queryBuilderVariableName = 'queryBuilder';
        if (null !== $queryBuilderArgument && $this->isVariable($queryBuilderArgument)) {
            $queryBuilderVariableName = $this->getName($queryBuilderArgument->value);
        }

        return (string) $queryBuilderVariableName;
    }

    private function addQueryBuilderBackendWorkspaceRestrictionNode(
        string $queryBuilderVariableName,
        Node $positionNode
    ): void {
        $newNode = $this->nodeFactory->createMethodCall(
            $this->nodeFactory->createMethodCall(
                $this->nodeFactory->createMethodCall($queryBuilderVariableName, 'getRestrictions'),
                'removeAll'
            ),
            'add',
            [
                $this->nodeFactory->createStaticCall(
                    GeneralUtility::class,
                    self::MAKE_INSTANCE,
                    [$this->nodeFactory->createClassConstReference(BackendWorkspaceRestriction::class)]
                ),
            ]
        );
        $this->addNodeBeforeNode($newNode, $positionNode);
    }

    private function addQueryBuilderDeletedRestrictionNode(
        string $queryBuilderVariableName,
        StaticCall $node,
        Node $positionNode
    ): void {
        $useDeleteClauseArgument = $node->args[7] ?? null;
        $useDeleteClause = null !== $useDeleteClauseArgument ? $this->valueResolver->getValue(
            $useDeleteClauseArgument->value
        ) : true;

        if (false === $useDeleteClause) {
            return;
        }

        $deletedRestrictionNode = $this->nodeFactory->createMethodCall(
            $this->nodeFactory->createMethodCall($queryBuilderVariableName, 'getRestrictions'),
            'add',
            [
                $this->nodeFactory->createStaticCall(
                    GeneralUtility::class,
                    self::MAKE_INSTANCE,
                    [$this->nodeFactory->createClassConstReference(DeletedRestriction::class)]
                ),
            ]
        );

        if ($useDeleteClause) {
            $this->addNodeBeforeNode($deletedRestrictionNode, $positionNode);

            return;
        }

        if (null === $useDeleteClauseArgument) {
            return;
        }

        $ifNode = new If_($useDeleteClauseArgument->value);
        $ifNode->stmts[] = new Expression($deletedRestrictionNode);

        $this->addNodeBeforeNode($ifNode, $positionNode);
    }

    private function addQueryBuilderSelectNode(
        string $queryBuilderVariableName,
        StaticCall $node,
        Node $positionNode
    ): void {
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
        $queryBuilderWhereNode = $this->nodeFactory->createMethodCall(
            $this->nodeFactory->createMethodCall(
                $this->nodeFactory->createMethodCall($queryBuilderVariableName, 'select', ['*']),
                'from',
                [$node->args[0]->value]
            ),
            'where',
            [$queryBuilderWhereExpressionNode]
        );
        $this->addNodeBeforeNode($queryBuilderWhereNode, $positionNode);
    }

    private function addQueryWhereNode(string $queryBuilderVariableName, StaticCall $node, Node $positionNode): void
    {
        $whereClauseArgument = $node->args[3] ?? null;
        $whereClause = null !== $whereClauseArgument ? $this->valueResolver->getValue($whereClauseArgument->value) : '';

        if ('' === $whereClause) {
            return;
        }

        $whereClauseNode = $this->nodeFactory->createMethodCall($queryBuilderVariableName, 'andWhere', [
            $this->nodeFactory->createStaticCall(QueryHelper::class, 'stripLogicalOperatorPrefix', [$node->args[3]]),
        ]);

        if ($whereClause) {
            $this->addNodeBeforeNode($whereClauseNode, $positionNode);

            return;
        }

        if (null === $whereClauseArgument) {
            return;
        }

        $ifNode = new If_($whereClauseArgument->value);
        $ifNode->stmts[] = new Expression($whereClauseNode);

        $this->addNodeBeforeNode($ifNode, $positionNode);
    }

    private function addQueryGroupByNode(string $queryBuilderVariableName, StaticCall $node, Node $positionNode): void
    {
        $groupByArgument = $node->args[4] ?? null;
        $groupBy = null !== $groupByArgument ? $this->valueResolver->getValue($groupByArgument->value) : '';

        if ('' === $groupBy) {
            return;
        }

        $groupByNode = $this->nodeFactory->createMethodCall($queryBuilderVariableName, 'groupBy', [
            $this->nodeFactory->createStaticCall(QueryHelper::class, 'parseGroupBy', [$node->args[4]]),
        ]);

        if ($groupBy) {
            $this->addNodeBeforeNode($groupByNode, $positionNode);

            return;
        }

        if (null === $groupByArgument) {
            return;
        }

        $ifNode = new If_(new NotIdentical($groupByArgument->value, new String_('')));
        $ifNode->stmts[] = new Expression($groupByNode);

        $this->addNodeBeforeNode($ifNode, $positionNode);
    }

    private function addOrderByNode(string $queryBuilderVariableName, StaticCall $node, Node $positionNode): void
    {
        $orderByArgument = $node->args[5] ?? null;
        $orderBy = null !== $orderByArgument ? $this->valueResolver->getValue($orderByArgument->value) : '';

        if ('' === $orderBy || 'null' === $orderBy) {
            return;
        }

        if (null === $orderByArgument) {
            return;
        }

        $orderByNode = new Foreach_(
            $this->nodeFactory->createStaticCall(QueryHelper::class, 'parseOrderBy', [$orderByArgument->value]),
            new Variable('orderPair')
        );
        $orderByNode->stmts[] = new Expression(
            new Assign(
                $this->nodeFactory->createFuncCall('list', [new Variable('fieldName'), new Variable('order')]),
                new Variable('orderPair')
            )
        );
        $orderByNode->stmts[] = new Expression($this->nodeFactory->createMethodCall(
            $queryBuilderVariableName,
            'addOrderBy',
            [new Variable('fieldName'), new Variable('order')]
        ));

        if ($orderBy) {
            $this->addNodeBeforeNode($orderByNode, $positionNode);

            return;
        }

        $ifNode = new If_(new NotIdentical($orderByArgument->value, new String_('')));
        $ifNode->stmts[] = $orderByNode;

        $this->addNodeBeforeNode($ifNode, $positionNode);
    }

    private function addLimitNode(string $queryBuilderVariableName, StaticCall $node, Node $positionNode): void
    {
        $limitArgument = $node->args[6] ?? null;
        $limit = null !== $limitArgument ? $this->valueResolver->getValue($limitArgument->value) : '';

        if ('' === $limit) {
            return;
        }

        if (null === $limitArgument) {
            return;
        }

        $limitNode = new If_($this->nodeFactory->createFuncCall('strpos', [$limitArgument->value, ',']));
        $limitNode->stmts[] = new Expression(
            new Assign(
                new Variable(self::LIMIT_OFFSET_AND_MAX),
                $this->nodeFactory->createStaticCall(
                    GeneralUtility::class,
                    'intExplode',
                    [new String_(','), new Variable('limit')]
                )
            )
        );
        $limitNode->stmts[] = new Expression($this->nodeFactory->createMethodCall(
            $queryBuilderVariableName,
            'setFirstResult',
            [new Int_(new ArrayDimFetch(new Variable(self::LIMIT_OFFSET_AND_MAX), new LNumber(0)))]
        ));
        $limitNode->stmts[] = new Expression(
            $this->nodeFactory->createMethodCall($queryBuilderVariableName, 'setMaxResults', [
                new Int_(new ArrayDimFetch(new Variable(self::LIMIT_OFFSET_AND_MAX), new LNumber(1))),
            ])
        );

        $limitNode->else = new Else_();
        $limitNode->else->stmts[] = new Expression($this->nodeFactory->createMethodCall(
            $queryBuilderVariableName,
            'setMaxResults',
            [new Int_(new Variable('limit'))]
        ));

        if ($limit) {
            $this->addNodeBeforeNode($limitNode, $positionNode);

            return;
        }

        $ifNode = new If_(new NotIdentical($limitArgument->value, new String_('')));
        $ifNode->stmts[] = $limitNode;

        $this->addNodeBeforeNode($ifNode, $positionNode);
    }
}

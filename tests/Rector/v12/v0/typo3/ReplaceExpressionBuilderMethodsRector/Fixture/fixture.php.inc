<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\ReplaceExpressionBuilderMethodsRector\Fixture;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Expression\ExpressionBuilder;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/** @var QueryBuilder $queryBuilder */
$queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('test');

$testConstraint = $queryBuilder->expr()->eq('foo', $queryBuilder->createNamedParameter('bar'));

$rows = $queryBuilder
    ->select('*')
    ->from('test')
    ->where(
        $queryBuilder->expr()->andX($testConstraint),
        $queryBuilder->expr()->orX($testConstraint)
    )
    ->executeQuery()
    ->fetchAllAssociative();

$singleAndConstraint = $queryBuilder->expr()->andX($testConstraint);
$singleOrConstraint = $queryBuilder->expr()->orX($testConstraint);

/** @var ExpressionBuilder $expr */
$expr = $queryBuilder->expr();
$singleAndConstraint = $expr->andX($testConstraint);
$singleOrConstraint = $expr->orX($testConstraint);

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\ReplaceExpressionBuilderMethodsRector\Fixture;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Expression\ExpressionBuilder;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/** @var QueryBuilder $queryBuilder */
$queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('test');

$testConstraint = $queryBuilder->expr()->eq('foo', $queryBuilder->createNamedParameter('bar'));

$rows = $queryBuilder
    ->select('*')
    ->from('test')
    ->where(
        $queryBuilder->expr()->and($testConstraint),
        $queryBuilder->expr()->or($testConstraint)
    )
    ->executeQuery()
    ->fetchAllAssociative();

$singleAndConstraint = $queryBuilder->expr()->and($testConstraint);
$singleOrConstraint = $queryBuilder->expr()->or($testConstraint);

/** @var ExpressionBuilder $expr */
$expr = $queryBuilder->expr();
$singleAndConstraint = $expr->and($testConstraint);
$singleOrConstraint = $expr->or($testConstraint);

?>

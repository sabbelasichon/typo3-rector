<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\MigrateQueryBuilderExecuteRector\Fixture;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$table = 'foo';

/** @var QueryBuilder $queryBuilder */
$queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($table);

$rows = $queryBuilder
    ->select('*')
    ->from($table)
    ->execute()
    ->fetchAllAssociative();

$rowsAddSelect = $queryBuilder
    ->addSelect('*')
    ->from($table)
    ->execute()
    ->fetchAllAssociative();

$rowsLiteral = $queryBuilder
    ->selectLiteral('*')
    ->from($table)
    ->execute()
    ->fetchAllAssociative();

$rowsAddSelectLiteral = $queryBuilder
    ->addSelectLiteral('*')
    ->from($table)
    ->execute()
    ->fetchAllAssociative();

$count = $queryBuilder
    ->count('*')
    ->from($table)
    ->execute()
    ->fetchAllAssociative();

$updatedRows = $queryBuilder
    ->update($table)
    ->set('foo', 'bar')
    ->execute();

$insertedRows = $queryBuilder
    ->insert($table)
    ->values(['foo', 'bar'])
    ->execute();

$deletedRows = $queryBuilder
    ->delete($table)
    ->execute();

/** @var QueryBuilder $queryBuilderSplit */
$queryBuilderSplit = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($table);

$test = $queryBuilderSplit
    ->from($table);

$result = $test
    ->select('*')
    ->execute();

/** @var QueryBuilder $queryBuilderSplitSecond */
$queryBuilderSplitSecond = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($table);

$testSecond = $queryBuilderSplitSecond
    ->select('*')
    ->from($table);

$result = $testSecond
    ->execute();

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\MigrateQueryBuilderExecuteRector\Fixture;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$table = 'foo';

/** @var QueryBuilder $queryBuilder */
$queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($table);

$rows = $queryBuilder
    ->select('*')->from($table)->executeQuery()
    ->fetchAllAssociative();

$rowsAddSelect = $queryBuilder
    ->addSelect('*')->from($table)->executeQuery()
    ->fetchAllAssociative();

$rowsLiteral = $queryBuilder
    ->selectLiteral('*')->from($table)->executeQuery()
    ->fetchAllAssociative();

$rowsAddSelectLiteral = $queryBuilder
    ->addSelectLiteral('*')->from($table)->executeQuery()
    ->fetchAllAssociative();

$count = $queryBuilder
    ->count('*')->from($table)->executeQuery()
    ->fetchAllAssociative();

$updatedRows = $queryBuilder
    ->update($table)->set('foo', 'bar')->executeStatement();

$insertedRows = $queryBuilder
    ->insert($table)->values(['foo', 'bar'])->executeStatement();

$deletedRows = $queryBuilder->delete($table)->executeStatement();

/** @var QueryBuilder $queryBuilderSplit */
$queryBuilderSplit = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($table);

$test = $queryBuilderSplit
    ->from($table);

$result = $test->select('*')->executeQuery();

/** @var QueryBuilder $queryBuilderSplitSecond */
$queryBuilderSplitSecond = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($table);

$testSecond = $queryBuilderSplitSecond
    ->select('*')
    ->from($table);

$result = $testSecond->executeQuery();

?>

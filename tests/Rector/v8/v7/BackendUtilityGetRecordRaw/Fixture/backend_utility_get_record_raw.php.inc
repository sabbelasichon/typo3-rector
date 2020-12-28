<?php

use TYPO3\CMS\Backend\Utility\BackendUtility;

$table = 'fe_users';
$where = 'uid > 5';
$fields = ['uid', 'pid'];
$record = BackendUtility::getRecordRaw($table, $where, $fields);

?>
-----
<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryHelper;
use TYPO3\CMS\Backend\Utility\BackendUtility;

$table = 'fe_users';
$where = 'uid > 5';
$fields = ['uid', 'pid'];

$queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($table);
$queryBuilder->getRestrictions()->removeAll();

$record = $queryBuilder->select(GeneralUtility::trimExplode(',', $fields, true))->from($table)->where(QueryHelper::stripLogicalOperatorPrefix($where))->execute()->fetch();

?>

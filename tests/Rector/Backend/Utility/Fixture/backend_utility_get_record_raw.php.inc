<?php

use TYPO3\CMS\Backend\Utility\BackendUtility;

$table = 'fe_users';
$where = 'uid > 5';
$fields = ['uid', 'pid'];
$record = BackendUtility::getRecordRaw($table, $where, $fields);

?>
-----
<?php

use TYPO3\CMS\Backend\Utility\BackendUtility;

$table = 'fe_users';
$where = 'uid > 5';
$fields = ['uid', 'pid'];

$queryBuilder = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Database\ConnectionPool::class)->getQueryBuilderForTable($table);
$queryBuilder->getRestrictions()->removeAll();

$record = $queryBuilder->select(\TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',', $fields, true))->from($table)->where(\TYPO3\CMS\Core\Database\Query\QueryHelper::stripLogicalOperatorPrefix($where))->execute()->fetch();

?>

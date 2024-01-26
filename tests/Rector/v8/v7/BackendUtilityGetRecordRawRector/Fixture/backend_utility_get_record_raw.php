<?php

use TYPO3\CMS\Backend\Utility\BackendUtility;

$table = 'fe_users';
$where = 'uid > 5';
$fields = ['uid', 'pid'];
$record = BackendUtility::getRecordRaw($table, $where, $fields);

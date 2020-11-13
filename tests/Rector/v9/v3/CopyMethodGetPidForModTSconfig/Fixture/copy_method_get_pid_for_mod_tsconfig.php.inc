<?php

use TYPO3\CMS\Backend\Utility\BackendUtility;

$modPid1 = BackendUtility::getPidForModTSconfig('pages', 1, 2);

$table = 'pages';
$uid = 1;
$pid = 2;
$modPid2 = BackendUtility::getPidForModTSconfig($table, $uid, $pid);

?>
-----
<?php

use TYPO3\CMS\Core\Utility\MathUtility;
use TYPO3\CMS\Backend\Utility\BackendUtility;
$table = 'pages';
$modPid1 = $table === 'pages' && MathUtility::canBeInterpretedAsInteger(1) ? 1 : 2;
$table = 'pages';
$uid = 1;
$pid = 2;
$modPid2 = $table === 'pages' && MathUtility::canBeInterpretedAsInteger($uid) ? $uid : $pid;

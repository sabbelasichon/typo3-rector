<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Recordlist\RecordList\DatabaseRecordList;

$databaseRecordList = GeneralUtility::makeInstance(DatabaseRecordList::class);
$thumbCode = $databaseRecordList->thumbCode([], 'foo', 'bar');

$uri = $databaseRecordList->requestUri();

?>
-----
<?php

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Recordlist\RecordList\DatabaseRecordList;

$databaseRecordList = GeneralUtility::makeInstance(DatabaseRecordList::class);
$thumbCode = BackendUtility::thumbCode([], 'foo', 'bar');

$uri = $databaseRecordList->listURL();

?>

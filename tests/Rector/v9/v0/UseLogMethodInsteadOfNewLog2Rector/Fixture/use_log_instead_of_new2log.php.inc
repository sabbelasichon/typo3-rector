<?php

use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$dataHandler = GeneralUtility::makeInstance(DataHandler::class);
$logEntryUid1 = $dataHandler->newlog2('Foo', 'pages', 1, null, 0);
$logEntryUid2 = $dataHandler->newlog2('Foo', 'tt_content', 1, 2, 1);
$logEntryUid3 = $dataHandler->newlog2('Foo', 'tt_content', 1);

?>
-----
<?php

use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$dataHandler = GeneralUtility::makeInstance(DataHandler::class);
$propArr = $dataHandler->getRecordProperties('pages', 1);
$pid = $propArr['pid'];

$logEntryUid1 = $dataHandler->log('pages', 1, 0, 0, 0, 'Foo', -1, [], $dataHandler->eventPid('pages', 1, $pid));
$logEntryUid2 = $dataHandler->log('tt_content', 1, 0, 0, 1, 'Foo', -1, [], $dataHandler->eventPid('tt_content', 1, 2));
$propArr = $dataHandler->getRecordProperties('tt_content', 1);
$pid = $propArr['pid'];

$logEntryUid3 = $dataHandler->log('tt_content', 1, 0, 0, 0, 'Foo', -1, [], $dataHandler->eventPid('tt_content', 1, $pid));

?>

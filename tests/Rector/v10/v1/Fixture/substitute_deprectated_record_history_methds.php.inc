<?php

$recordHistory = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Backend\History\RecordHistory::class);
$historyEntry = $recordHistory->getHistoryEntry();
$historyData = $recordHistory->getHistoryData();
$shouldPerformRollback = $recordHistory->shouldPerformRollback();

$propChangelog = $recordHistory->changeLog;
$propLastHistoryEntry = $recordHistory->lastHistoryEntry;

$historyChangelog = $recordHistory->createChangeLog();
$getElementData = $recordHistory->getElementData();
$recordHistory->performRollback();
$diff = $recordHistory->createMultipleDiff([]);
$recordHistory->setLastHistoryEntry(12345);

?>
-----
<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Backend\History\RecordHistory;
$recordHistory = GeneralUtility::makeInstance(RecordHistory::class);

$propChangelog = $recordHistory->getChangeLog();
$propLastHistoryEntry = $recordHistory->getLastHistoryEntryNumber();

$historyChangelog = $recordHistory->getChangeLog();
$getElementData = $recordHistory->getElementInformation();
GeneralUtility::makeInstance(\TYPO3\CMS\Backend\History\RecordHistoryRollback::class)->performRollback('!!!insertRollBackFieldsHere!!!',$recordHistory->getDiff($recordHistory->getChangeLog()));
$diff = $recordHistory->getDiff($data);
$recordHistory->setLastHistoryEntryNumber(12345);
?>

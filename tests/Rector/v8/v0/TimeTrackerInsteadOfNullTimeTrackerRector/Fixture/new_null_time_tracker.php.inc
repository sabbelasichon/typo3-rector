<?php

use TYPO3\CMS\Core\TimeTracker\NullTimeTracker;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

$objectManager = GeneralUtility::makeInstance(ObjectManager::class);
$timeTracker1 = new NullTimeTracker();
$timeTracker2 = GeneralUtility::makeInstance(NullTimeTracker::class);
$timeTracker3 = $objectManager->get(NullTimeTracker::class);

if (!is_object($GLOBALS['TT'])) {
    $GLOBALS['TT'] = new NullTimeTracker;
    $GLOBALS['TT']->start();
}

?>
-----
<?php

use TYPO3\CMS\Core\TimeTracker\TimeTracker;
use TYPO3\CMS\Core\TimeTracker\NullTimeTracker;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

$objectManager = GeneralUtility::makeInstance(ObjectManager::class);
$timeTracker1 = new TimeTracker(false);
$timeTracker2 = GeneralUtility::makeInstance(TimeTracker::class, false);
$timeTracker3 = $objectManager->get(TimeTracker::class, false);

if (!is_object($GLOBALS['TT'])) {
    $GLOBALS['TT'] = new TimeTracker(false);
    $GLOBALS['TT']->start();
}

?>

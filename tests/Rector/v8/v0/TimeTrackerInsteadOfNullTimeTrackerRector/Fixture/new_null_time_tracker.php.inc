<?php

use TYPO3\CMS\Core\TimeTracker\NullTimeTracker;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

$objectManager = GeneralUtility::makeInstance(ObjectManager::class);
$timeTracker1 = new NullTimeTracker();
$timeTracker2 = GeneralUtility::makeInstance(NullTimeTracker::class);
$timeTracker3 = $objectManager->get(NullTimeTracker::class);

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

?>

<?php

use TYPO3\CMS\Core\TimeTracker\NullTimeTracker;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$someObject = GeneralUtility::makeInstance(NullTimeTracker::class);

?>
-----
<?php

use TYPO3\CMS\Core\TimeTracker\TimeTracker;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$someObject = GeneralUtility::makeInstance(TimeTracker::class, [false]);

?>

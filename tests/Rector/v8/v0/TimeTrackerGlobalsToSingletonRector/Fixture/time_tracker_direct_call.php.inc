<?php

$GLOBALS['TT']->setTSlogMessage('content');

?>
-----
<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\TimeTracker\TimeTracker;
GeneralUtility::makeInstance(TimeTracker::class)->setTSlogMessage('content');

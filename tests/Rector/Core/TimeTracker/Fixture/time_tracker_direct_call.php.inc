<?php

$GLOBALS['TT']->setTSlogMessage('content');

?>
-----
<?php

\TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\TimeTracker\TimeTracker::class)->setTSlogMessage('content');

?>

<?php

use TYPO3\CMS\Backend\Utility\BackendUtility;

$copyright = BackendUtility::TYPO3_copyRightNotice();

?>
-----
<?php

use TYPO3\CMS\Backend\Utility\BackendUtility;

$copyright = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Information\Typo3Information::class)->getCopyrightNotice();

?>

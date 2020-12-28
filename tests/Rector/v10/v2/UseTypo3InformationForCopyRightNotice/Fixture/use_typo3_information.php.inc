<?php

use TYPO3\CMS\Backend\Utility\BackendUtility;

$copyright = BackendUtility::TYPO3_copyRightNotice();

?>
-----
<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Information\Typo3Information;
use TYPO3\CMS\Backend\Utility\BackendUtility;

$copyright = GeneralUtility::makeInstance(Typo3Information::class)->getCopyrightNotice();

?>

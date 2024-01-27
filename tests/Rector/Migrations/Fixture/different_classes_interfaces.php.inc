<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;

$arrayBrowser = GeneralUtility::makeInstance(\TYPO3\CMS\Lowlevel\Utility\ArrayBrowser::class);

?>
-----
<?php

use TYPO3\CMS\Backend\View\ArrayBrowser;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$arrayBrowser = GeneralUtility::makeInstance(ArrayBrowser::class);

?>

<?php

use TYPO3\CMS\Backend\Utility\BackendUtility;

$moduleName = 'record_edit';
$url = BackendUtility::getModuleUrl($moduleName);

?>
-----
<?php

use TYPO3\CMS\Backend\Utility\BackendUtility;

$moduleName = 'record_edit';
$url = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Backend\Routing\UriBuilder::class)->buildUriFromRoute($moduleName);

?>

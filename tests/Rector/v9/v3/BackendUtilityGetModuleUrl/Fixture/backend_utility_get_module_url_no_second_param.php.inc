<?php

use TYPO3\CMS\Backend\Utility\BackendUtility;

$moduleName = 'record_edit';
$url = BackendUtility::getModuleUrl($moduleName);

?>
-----
<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Backend\Utility\BackendUtility;

$moduleName = 'record_edit';
$url = GeneralUtility::makeInstance(UriBuilder::class)->buildUriFromRoute($moduleName);

?>

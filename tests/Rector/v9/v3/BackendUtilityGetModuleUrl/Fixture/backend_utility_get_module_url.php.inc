<?php

use TYPO3\CMS\Backend\Utility\BackendUtility;

$moduleName = 'record_edit';
$params = ['pid' => 2];
$url = BackendUtility::getModuleUrl($moduleName, $params);

?>
-----
<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Backend\Utility\BackendUtility;
$moduleName = 'record_edit';
$params = ['pid' => 2];
$url = GeneralUtility::makeInstance(UriBuilder::class)->buildUriFromRoute($moduleName, $params);

<?php

use TYPO3\CMS\Backend\Utility\BackendUtility;

$pid = 2;
$params = '&edit[pages]['.$pid.']=new&returnNewPageId=1';
$url = BackendUtility::editOnClick($params);

?>
-----
<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Backend\Utility\BackendUtility;
$pid = 2;
$params = '&edit[pages]['.$pid.']=new&returnNewPageId=1';
$url = GeneralUtility::makeInstance(UriBuilder::class)->buildUriFromRoute('record_edit') . $params . '&returnUrl=' . rawurlencode(GeneralUtility::getIndpEnv('REQUEST_URI'));

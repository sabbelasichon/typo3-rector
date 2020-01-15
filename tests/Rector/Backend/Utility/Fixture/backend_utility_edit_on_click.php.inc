<?php

use TYPO3\CMS\Backend\Utility\BackendUtility;

$pid = 2;
$params = '&edit[pages]['.$pid.']=new&returnNewPageId=1';
$url = BackendUtility::editOnClick($params);

?>
-----
<?php

use TYPO3\CMS\Backend\Utility\BackendUtility;

$pid = 2;
$params = '&edit[pages]['.$pid.']=new&returnNewPageId=1';
$url = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Backend\Routing\UriBuilder::class)->buildUriFromRoute('record_edit') . $params . '&returnUrl=' . rawurlencode(\TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('REQUEST_URI'));

?>

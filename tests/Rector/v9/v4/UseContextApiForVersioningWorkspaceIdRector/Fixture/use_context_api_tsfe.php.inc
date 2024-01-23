<?php

use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

$workspaceId = null;
$workspaceId = $workspaceId ?? $GLOBALS['TSFE']->sys_page->versioningWorkspaceId;

/** @var TypoScriptFrontendController $typoScriptFrontendController */
$typoScriptFrontendController = $GLOBALS['TSFE'];
$workspaceId = $typoScriptFrontendController->sys_page->versioningWorkspaceId;

?>
-----
<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

$workspaceId = null;
$workspaceId = $workspaceId ?? GeneralUtility::makeInstance(Context::class)->getPropertyFromAspect('workspace', 'id', 0);

/** @var TypoScriptFrontendController $typoScriptFrontendController */
$typoScriptFrontendController = $GLOBALS['TSFE'];
$workspaceId = GeneralUtility::makeInstance(Context::class)->getPropertyFromAspect('workspace', 'id', 0);

?>

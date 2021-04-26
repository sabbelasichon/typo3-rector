<?php

$GLOBALS['TSFE']->loginAllowedInBranch = false;
$previewBeUserUid = $GLOBALS['TSFE']->ADMCMD_preview_BEUSER_uid;
$workspacePreview = $GLOBALS['TSFE']->workspacePreview;
$loginAllowedInBranch = $GLOBALS['TSFE']->loginAllowedInBranch;

?>
-----
<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Context\Context;
$GLOBALS['TSFE']->loginAllowedInBranch = false;
$previewBeUserUid = GeneralUtility::makeInstance(Context::class)->getPropertyFromAspect('backend.user', 'id', 0);
$workspacePreview = GeneralUtility::makeInstance(Context::class)->getPropertyFromAspect('workspace', 'id', 0);
$loginAllowedInBranch = $GLOBALS['TSFE']->checkIfLoginAllowedInBranch();

?>

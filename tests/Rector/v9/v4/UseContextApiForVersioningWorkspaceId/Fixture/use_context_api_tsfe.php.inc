<?php

$workspaceId = null;
$workspaceId = $workspaceId ?? $GLOBALS['TSFE']->sys_page->versioningWorkspaceId;

$GLOBALS['TSFE']->sys_page->versioningWorkspaceId = 1;

?>
-----
<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Context\Context;

$workspaceId = null;
$workspaceId = $workspaceId ?? GeneralUtility::makeInstance(Context::class)->getPropertyFromAspect('workspace', 'id', 0);
$GLOBALS['TSFE']->sys_page->versioningWorkspaceId = 1;

?>

<?php

$workspaceId = null;
$workspaceId = $workspaceId ?? $GLOBALS['TSFE']->sys_page->versioningWorkspaceId;

$GLOBALS['TSFE']->sys_page->versioningWorkspaceId = 1;

?>
-----
<?php

$workspaceId = null;
$workspaceId = $workspaceId ?? \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Context\Context::class)->getPropertyFromAspect('workspace', 'id', 0);

$GLOBALS['TSFE']->sys_page->versioningWorkspaceId = 1;

?>

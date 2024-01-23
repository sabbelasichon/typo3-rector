<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Page\PageRepository;

$pageRepository = GeneralUtility::makeInstance(PageRepository::class);
$workspaceId = null;
$workspaceId = $workspaceId ?? $pageRepository->versioningWorkspaceId;

$workspaceId = $pageRepository->versioningWorkspaceId;

$stdClass = new stdClass();
$stdClassVersioningWorkspaceId = $stdClass->versioningWorkspaceId;

?>
-----
<?php

use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Page\PageRepository;

$pageRepository = GeneralUtility::makeInstance(PageRepository::class);
$workspaceId = null;
$workspaceId = $workspaceId ?? GeneralUtility::makeInstance(Context::class)->getPropertyFromAspect('workspace', 'id', 0);

$workspaceId = GeneralUtility::makeInstance(Context::class)->getPropertyFromAspect('workspace', 'id', 0);

$stdClass = new stdClass();
$stdClassVersioningWorkspaceId = $stdClass->versioningWorkspaceId;

?>

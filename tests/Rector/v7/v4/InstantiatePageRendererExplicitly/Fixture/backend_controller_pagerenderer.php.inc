<?php

use TYPO3\CMS\Backend\Controller\BackendController;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$backendController = GeneralUtility::makeInstance(BackendController::class);
$pageRenderer = $backendController->getPageRenderer();

?>
-----
<?php

use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Backend\Controller\BackendController;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$backendController = GeneralUtility::makeInstance(BackendController::class);
$pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);

?>

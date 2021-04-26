<?php

use TYPO3\CMS\Backend\Controller\PageLayoutController;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$pageLayoutController = GeneralUtility::makeInstance(PageLayoutController::class);
$pageLayoutController->printContent();

?>
-----
<?php

use TYPO3\CMS\Backend\Controller\PageLayoutController;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$pageLayoutController = GeneralUtility::makeInstance(PageLayoutController::class);
echo $pageLayoutController->getModuleTemplate()->renderContent();

?>

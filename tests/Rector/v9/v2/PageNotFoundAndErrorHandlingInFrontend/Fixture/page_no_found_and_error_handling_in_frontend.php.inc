<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
$tsfeController = GeneralUtility::makeInstance(TypoScriptFrontendController::class);
$tsfeController->checkPageUnavailableHandler();

?>
-----
<?php

use TYPO3\CMS\Frontend\Controller\ErrorController;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
$tsfeController = GeneralUtility::makeInstance(TypoScriptFrontendController::class);
$errorController = GeneralUtility::makeInstance(ErrorController::class);
$errorController->unavailableAction($request);

<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\RemoveRedundantFeLoginModeMethodsRector\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

$feUserAuth = GeneralUtility::makeInstance(FrontendUserAuthentication::class);
$feUserAuth->hideActiveLogin();

$tsfe = GeneralUtility::makeInstance(TypoScriptFrontendController::class);
$tsfe->checkIfLoginAllowedInBranch();

$GLOBALS['TSFE']->checkIfLoginAllowedInBranch();
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\RemoveRedundantFeLoginModeMethodsRector\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

$feUserAuth = GeneralUtility::makeInstance(FrontendUserAuthentication::class);

$tsfe = GeneralUtility::makeInstance(TypoScriptFrontendController::class);
$tsfe->checkIfLoginAllowedInBranch();

$GLOBALS['TSFE']->checkIfLoginAllowedInBranch();
?>

<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

$tsfe = GeneralUtility::makeInstance(TypoScriptFrontendController::class);
$tsfe->processOutput();

?>
-----
<?php

use TYPO3\CMS\Core\Http\Response;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

$tsfe = GeneralUtility::makeInstance(TypoScriptFrontendController::class);
$tsfe->applyHttpHeadersToResponse(new Response());
$tsfe->processContentForOutput();

?>

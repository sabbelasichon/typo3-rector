<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\RemoveTSFEMetaCharSetCallsRector\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

$foo = $GLOBALS['TSFE']->metaCharset;

/** @var TypoScriptFrontendController $tsfe */
$tsfe = GeneralUtility::makeInstance(TypoScriptFrontendController::class);
$bar = $tsfe->metaCharset;

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\RemoveTSFEMetaCharSetCallsRector\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

$foo = 'utf-8';

/** @var TypoScriptFrontendController $tsfe */
$tsfe = GeneralUtility::makeInstance(TypoScriptFrontendController::class);
$bar = 'utf-8';

?>

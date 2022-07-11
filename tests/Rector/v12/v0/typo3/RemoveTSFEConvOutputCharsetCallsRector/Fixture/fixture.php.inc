<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\RemoveTSFEConvOutputCharsetCallsRector\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/** @var TypoScriptFrontendController $tsfe */
$tsfe = GeneralUtility::makeInstance(TypoScriptFrontendController::class);

$content = 'test';
$test = $GLOBALS['TSFE']->convOutputCharset('content');
$foo = $GLOBALS['TSFE']->convOutputCharset($content);
$bar = $tsfe->convOutputCharset('content');
$baz = $tsfe->convOutputCharset($content);

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\RemoveTSFEConvOutputCharsetCallsRector\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/** @var TypoScriptFrontendController $tsfe */
$tsfe = GeneralUtility::makeInstance(TypoScriptFrontendController::class);

$content = 'test';
$test = 'content';
$foo = $content;
$bar = 'content';
$baz = $content;

?>

<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v3\AddServerRequestArgumentToGeneralUtilityMethodsRector\Fixture;

use Foo\Bar;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$isOnCurrentHost = GeneralUtility::isOnCurrentHost($url, $request);
$sanitizedUrl = GeneralUtility::sanitizeLocalUrl($url, $GLOBALS['TYPO3_REQUEST']);
$absoluteUrl = GeneralUtility::locationHeaderUrl(...$arguments);
$namedUrl = GeneralUtility::sanitizeLocalUrl(url: $url);
$callable = GeneralUtility::locationHeaderUrl(...);
$other = GeneralUtility::getIndpEnv('HTTP_HOST');
$otherClass = Bar::sanitizeLocalUrl($url);

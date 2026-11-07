<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v3\AddServerRequestArgumentToGeneralUtilityMethodsRector\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;

$isOnCurrentHost = GeneralUtility::isOnCurrentHost($url);
$sanitizedUrl = GeneralUtility::sanitizeLocalUrl($url);
$absoluteUrl = GeneralUtility::locationHeaderUrl('/path');

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v3\AddServerRequestArgumentToGeneralUtilityMethodsRector\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;

$isOnCurrentHost = GeneralUtility::isOnCurrentHost($url, $GLOBALS['TYPO3_REQUEST']);
$sanitizedUrl = GeneralUtility::sanitizeLocalUrl($url, $GLOBALS['TYPO3_REQUEST']);
$absoluteUrl = GeneralUtility::locationHeaderUrl('/path', $GLOBALS['TYPO3_REQUEST']);

?>

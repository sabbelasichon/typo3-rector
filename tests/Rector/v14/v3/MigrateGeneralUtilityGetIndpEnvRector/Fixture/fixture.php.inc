<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v3\MigrateGeneralUtilityGetIndpEnvRector\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;

$siteUrl = GeneralUtility::getIndpEnv('TYPO3_SITE_URL');
$host = GeneralUtility::getIndpEnv('HTTP_HOST');
$isHttps = GeneralUtility::getIndpEnv('TYPO3_SSL');

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v3\MigrateGeneralUtilityGetIndpEnvRector\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;

$siteUrl = $GLOBALS['TYPO3_REQUEST']->getAttribute('normalizedParams')->getSiteUrl();
$host = $GLOBALS['TYPO3_REQUEST']->getAttribute('normalizedParams')->getHttpHost();
$isHttps = $GLOBALS['TYPO3_REQUEST']->getAttribute('normalizedParams')->isHttps();

?>

<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateTypoScriptFrontendControllerMethodCallsRector\Fixture;

$requestedId = $GLOBALS['TSFE']->getRequestedId();
$language = $GLOBALS['TSFE']->getLanguage();
$site = $GLOBALS['TSFE']->getSite();
$pageArguments = $GLOBALS['TSFE']->getPageArguments();

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateTypoScriptFrontendControllerMethodCallsRector\Fixture;

$requestedId = $GLOBALS['TYPO3_REQUEST']->getAttribute('routing')->getPageId();
$language = $GLOBALS['TYPO3_REQUEST']->getAttribute('language') ?? $GLOBALS['TYPO3_REQUEST']->getAttribute('site')->getDefaultLanguage();
$site = $GLOBALS['TYPO3_REQUEST']->getAttribute('site');
$pageArguments = $GLOBALS['TYPO3_REQUEST']->getAttribute('routing');

?>

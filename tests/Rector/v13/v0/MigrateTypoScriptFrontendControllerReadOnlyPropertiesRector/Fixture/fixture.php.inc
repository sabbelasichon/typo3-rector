<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateTypoScriptFrontendControllerReadOnlyPropertiesRector\Fixture;

$id = $GLOBALS['TSFE']->id;
$rootLine = $GLOBALS['TSFE']->rootLine;
$page = $GLOBALS['TSFE']->page;

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateTypoScriptFrontendControllerReadOnlyPropertiesRector\Fixture;

$id = $GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.page.information')->getId();
$rootLine = $GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.page.information')->getRootLine();
$page = $GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.page.information')->getPageRecord();

?>

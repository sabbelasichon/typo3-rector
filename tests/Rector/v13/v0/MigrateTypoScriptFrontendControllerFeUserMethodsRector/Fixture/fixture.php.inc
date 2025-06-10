<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateTypoScriptFrontendControllerFeUserMethodsRector\Fixture;

$GLOBALS['TSFE']->fe_user->setKey('ses', 'extension', 'value');
$GLOBALS['TSFE']->fe_user->getKey('ses', 'extension');
$GLOBALS['TSFE']->fe_user->storeSessionData();

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateTypoScriptFrontendControllerFeUserMethodsRector\Fixture;

$GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.user')->setKey('ses', 'extension', 'value');
$GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.user')->getKey('ses', 'extension');
$GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.user')->storeSessionData();

?>

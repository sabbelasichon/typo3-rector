<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v1\TemplateServiceToServerRequestFrontendTypoScriptAttributeRector\Fixture;

$setup = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_ext.']['settings.'];
$foo = $GLOBALS['TSFE']->tmpl->forceTemplateParsing;

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v1\TemplateServiceToServerRequestFrontendTypoScriptAttributeRector\Fixture;

$setup = $GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.typoscript')->getSetupArray()['plugin.']['tx_ext.']['settings.'];
$foo = $GLOBALS['TSFE']->tmpl->forceTemplateParsing;

?>

<?php

namespace Ssch\TYPO3Rector\Tests\Rector\CodeQuality\Rector\General\MoveExtensionUtilityRegisterPluginIntoTCAOverridesRector\Fixture;

$_EXTKEY = 'extension3';

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin($_EXTKEY, 'PluginName', 'My Title');
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin('extension3', 'Pi3', 'My Title');
// Once the rule RegisterPluginWithVendorNameRector does its job, we can move it afterwards
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin('Vendor.' . $_EXTKEY, 'PluginName', 'My Title');

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\CodeQuality\Rector\General\MoveExtensionUtilityRegisterPluginIntoTCAOverridesRector\Fixture;

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

$_EXTKEY = 'extension3';
// Once the rule RegisterPluginWithVendorNameRector does its job, we can move it afterwards
ExtensionUtility::registerPlugin('Vendor.' . $_EXTKEY, 'PluginName', 'My Title');

?>

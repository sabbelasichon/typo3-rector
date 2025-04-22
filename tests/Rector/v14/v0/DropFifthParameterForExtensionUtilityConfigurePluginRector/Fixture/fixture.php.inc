<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\DropFifthParameterForExtensionUtilityConfigurePluginRector\Fixture;

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

ExtensionUtility::configurePlugin('ExtensionName', 'PluginName', [], [], 'CType');
ExtensionUtility::configurePlugin('ExtensionName', 'PluginName', [], [], ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT);
ExtensionUtility::configurePlugin('ExtensionName', 'PluginName', []);

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\DropFifthParameterForExtensionUtilityConfigurePluginRector\Fixture;

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

ExtensionUtility::configurePlugin('ExtensionName', 'PluginName', [], []);
ExtensionUtility::configurePlugin('ExtensionName', 'PluginName', [], []);
ExtensionUtility::configurePlugin('ExtensionName', 'PluginName', []);

?>

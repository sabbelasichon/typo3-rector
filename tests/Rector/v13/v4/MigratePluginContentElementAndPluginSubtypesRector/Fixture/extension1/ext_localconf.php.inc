<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v4\MigratePluginContentElementAndPluginSubtypesRector\Fixture;

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

ExtensionUtility::configurePlugin('extensionName', 'PluginName', []);
ExtensionUtility::configurePlugin('extensionName', 'PluginName', [], [], 'list_type');
ExtensionUtility::configurePlugin('extensionName', 'PluginName', [], [], ExtensionUtility::PLUGIN_TYPE_PLUGIN);

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v4\MigratePluginContentElementAndPluginSubtypesRector\Fixture;

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

ExtensionUtility::configurePlugin('extensionName', 'PluginName', [], [], ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT);
ExtensionUtility::configurePlugin('extensionName', 'PluginName', [], [], ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT);
ExtensionUtility::configurePlugin('extensionName', 'PluginName', [], [], ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT);

?>

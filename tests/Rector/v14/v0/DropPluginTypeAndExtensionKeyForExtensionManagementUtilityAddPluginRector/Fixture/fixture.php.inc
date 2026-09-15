<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\DropPluginTypeAndExtensionKeyForExtensionManagementUtilityAddPluginRector\Fixture;

use TYPO3\CMS\Core\Schema\Struct\SelectItem;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

ExtensionManagementUtility::addPlugin(['title', 'signature', 'icon', 'group'], 'CType', 'extension_key');
ExtensionManagementUtility::addPlugin(['title', 'signature', 'icon', 'group'], 'list_type', 'extension_key');
ExtensionManagementUtility::addPlugin(['title', 'signature', 'icon', 'group'], ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT, 'extension_key');
ExtensionManagementUtility::addPlugin(['title', 'signature', 'icon', 'group'], ExtensionUtility::PLUGIN_TYPE_PLUGIN, 'extension_key');
ExtensionManagementUtility::addPlugin(['title', 'signature', 'icon', 'group'], null, 'extension_key');
ExtensionManagementUtility::addPlugin(['title', 'signature', 'icon', 'group'], 'CType');
ExtensionManagementUtility::addPlugin(['title', 'signature', 'icon', 'group'], 'list_type');
ExtensionManagementUtility::addPlugin(['title', 'signature', 'icon', 'group'], ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT);
ExtensionManagementUtility::addPlugin(new SelectItem('select', 'title', 'signature', 'icon', 'group'), 'CType', 'extension_key');
ExtensionManagementUtility::addPlugin($items, $type, $extensionKey);
ExtensionManagementUtility::addPlugin(['title', 'signature', 'icon', 'group'], 'FILE:EXT:my_extension/Configuration/FlexForms/Plugin.xml', 'extension_key');

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\DropPluginTypeAndExtensionKeyForExtensionManagementUtilityAddPluginRector\Fixture;

use TYPO3\CMS\Core\Schema\Struct\SelectItem;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

ExtensionManagementUtility::addPlugin(['title', 'signature', 'icon', 'group']);
ExtensionManagementUtility::addPlugin(['title', 'signature', 'icon', 'group']);
ExtensionManagementUtility::addPlugin(['title', 'signature', 'icon', 'group']);
ExtensionManagementUtility::addPlugin(['title', 'signature', 'icon', 'group']);
ExtensionManagementUtility::addPlugin(['title', 'signature', 'icon', 'group']);
ExtensionManagementUtility::addPlugin(['title', 'signature', 'icon', 'group']);
ExtensionManagementUtility::addPlugin(['title', 'signature', 'icon', 'group']);
ExtensionManagementUtility::addPlugin(['title', 'signature', 'icon', 'group']);
ExtensionManagementUtility::addPlugin(new SelectItem('select', 'title', 'signature', 'icon', 'group'));
ExtensionManagementUtility::addPlugin($items);
ExtensionManagementUtility::addPlugin(['title', 'signature', 'icon', 'group'], 'FILE:EXT:my_extension/Configuration/FlexForms/Plugin.xml');

?>

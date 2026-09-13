<?php

namespace Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\MigrateExtensionManagementUtilityAddTcaSelectItemToAddPluginRector\Fixture;

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

ExtensionManagementUtility::addTcaSelectItem(
    'tt_content',
    'CType',
    [
        'label' => 'my-label',
        'value' => 'my-content-element',
        'icon' => 'content-map',
        'group' => 'my-group',
        'description' => 'my-description',
    ]
);

ExtensionManagementUtility::addTcaSelectItem(
    'tt_content',
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT,
    [
        'label' => 'my-label',
        'value' => 'my-content-element',
        'icon' => 'content-map',
        'group' => 'my-group',
        'description' => 'my-description',
    ]
);
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\MigrateExtensionManagementUtilityAddTcaSelectItemToAddPluginRector\Fixture;

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

ExtensionManagementUtility::addPlugin(
    [
        'label' => 'my-label',
        'value' => 'my-content-element',
        'icon' => 'content-map',
        'group' => 'my-group',
        'description' => 'my-description',
    ]
);

ExtensionManagementUtility::addPlugin(
    [
        'label' => 'my-label',
        'value' => 'my-content-element',
        'icon' => 'content-map',
        'group' => 'my-group',
        'description' => 'my-description',
    ]
);
?>

<?php

namespace Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\MigrateExtensionManagementUtilityAddTcaSelectItemToAddPluginRector\Fixture;

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

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
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\MigrateExtensionManagementUtilityAddTcaSelectItemToAddPluginRector\Fixture;

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

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

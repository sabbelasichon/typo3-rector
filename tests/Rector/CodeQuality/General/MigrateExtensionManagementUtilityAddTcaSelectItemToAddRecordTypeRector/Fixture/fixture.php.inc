<?php

namespace Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\MigrateExtensionManagementUtilityAddTcaSelectItemToAddRecordTypeRector\Fixture;

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

namespace Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\MigrateExtensionManagementUtilityAddTcaSelectItemToAddRecordTypeRector\Fixture;

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

ExtensionManagementUtility::addRecordType(
    [
        'label' => 'my-label',
        'value' => 'my-content-element',
        'icon' => 'content-map',
        'group' => 'my-group',
        'description' => 'my-description',
    ],
    // TODO: Important! Add showItemList yourself
    ''
);

ExtensionManagementUtility::addRecordType(
    [
        'label' => 'my-label',
        'value' => 'my-content-element',
        'icon' => 'content-map',
        'group' => 'my-group',
        'description' => 'my-description',
    ],
    // TODO: Important! Add showItemList yourself
    ''
);
?>

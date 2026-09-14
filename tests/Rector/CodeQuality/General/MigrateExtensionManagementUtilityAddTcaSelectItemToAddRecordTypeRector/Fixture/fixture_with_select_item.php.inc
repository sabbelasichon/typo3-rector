<?php

namespace Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\MigrateExtensionManagementUtilityAddTcaSelectItemToAddRecordTypeRector\Fixture;

use TYPO3\CMS\Core\Schema\Struct\SelectItem;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

ExtensionManagementUtility::addTcaSelectItem(
    'tt_content',
    'CType',
    new SelectItem(
        'select',
        'my-label',
        'my-content-element',
        'my-icon',
        'my-group',
        'my-description',
    )
);
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\MigrateExtensionManagementUtilityAddTcaSelectItemToAddRecordTypeRector\Fixture;

use TYPO3\CMS\Core\Schema\Struct\SelectItem;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

ExtensionManagementUtility::addRecordType(
    new SelectItem(
        'select',
        'my-label',
        'my-content-element',
        'my-icon',
        'my-group',
        'my-description',
    ),
    // TODO: Important! Add showItemList yourself
    ''
);
?>

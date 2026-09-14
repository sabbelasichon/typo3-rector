<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v3\UseSelectItemInsteadOfArrayRector\Fixture\PHP80;

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

ExtensionManagementUtility::addTcaSelectItem(
    'tt_content',
    'my_field',
    [
        'my-label',
        'my-value',
        'my-icon',
        'my-group',
        'my-description',
    ]
);

ExtensionManagementUtility::addRecordType(
    [
        'my-label',
        'my-value',
        'my-icon',
        'my-group',
        'my-description',
    ], 'showItemList'
);

ExtensionManagementUtility::addPlugin(
    [
        'my-label',
        'my-value',
        'my-icon',
        'my-group',
        'my-description',
    ]
);

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v3\UseSelectItemInsteadOfArrayRector\Fixture\PHP80;

use TYPO3\CMS\Core\Schema\Struct\SelectItem;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

ExtensionManagementUtility::addTcaSelectItem(
    'tt_content',
    'my_field',
    new SelectItem(type: 'select', label: 'my-label', value: 'my-value', icon: 'my-icon', group: 'my-group', description: 'my-description')
);

ExtensionManagementUtility::addRecordType(
    new SelectItem(type: 'select', label: 'my-label', value: 'my-value', icon: 'my-icon', group: 'my-group', description: 'my-description'), 'showItemList'
);

ExtensionManagementUtility::addPlugin(
    new SelectItem(type: 'select', label: 'my-label', value: 'my-value', icon: 'my-icon', group: 'my-group', description: 'my-description')
);

?>

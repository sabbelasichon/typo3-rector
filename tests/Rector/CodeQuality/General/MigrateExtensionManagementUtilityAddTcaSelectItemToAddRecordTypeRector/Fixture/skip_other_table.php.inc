<?php

namespace Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\MigrateExtensionManagementUtilityAddTcaSelectItemToAddRecordTypeRector\Fixture;

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

ExtensionManagementUtility::addTcaSelectItem(
    'pages',
    'CType',
    [
        'label' => 'my-label',
        'value' => 'my-content-element',
    ]
);

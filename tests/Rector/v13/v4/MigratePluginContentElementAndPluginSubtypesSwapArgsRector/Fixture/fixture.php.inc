<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v4\MigratePluginContentElementAndPluginSubtypesSwapArgsRector\Fixture;

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

$pluginSignature = 'tx_rector_pi1';

// already migrated
ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:examples/Configuration/Flexforms/HtmlParser.xml',
    $pluginSignature,
);

ExtensionManagementUtility::addPiFlexFormValue(
    $pluginSignature,
    'FILE:EXT:examples/Configuration/Flexforms/HtmlParser.xml',
);

ExtensionManagementUtility::addPiFlexFormValue(
    'tx_rector_pi1',
    'FILE:EXT:examples/Configuration/Flexforms/HtmlParser.xml',
);

ExtensionManagementUtility::addPiFlexFormValue(
    $pluginSignature,
    'FILE:EXT:examples/Configuration/Flexforms/HtmlParser.xml',
    'list',
);

ExtensionManagementUtility::addPiFlexFormValue(
    'tx_rector_pi1',
    'FILE:EXT:examples/Configuration/Flexforms/HtmlParser.xml',
    'list',
);

ExtensionManagementUtility::addPiFlexFormValue(
    $pluginSignature,
    'FILE:EXT:examples/Configuration/Flexforms/HtmlParser.xml',
    '*',
);

ExtensionManagementUtility::addPiFlexFormValue(
    'tx_rector_pi1',
    'FILE:EXT:examples/Configuration/Flexforms/HtmlParser.xml',
    '*',
);

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v4\MigratePluginContentElementAndPluginSubtypesSwapArgsRector\Fixture;

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

$pluginSignature = 'tx_rector_pi1';

// already migrated
ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:examples/Configuration/Flexforms/HtmlParser.xml',
    $pluginSignature,
);

ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:examples/Configuration/Flexforms/HtmlParser.xml',
    $pluginSignature,
);

ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:examples/Configuration/Flexforms/HtmlParser.xml',
    'tx_rector_pi1',
);

ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:examples/Configuration/Flexforms/HtmlParser.xml',
    $pluginSignature,
);

ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:examples/Configuration/Flexforms/HtmlParser.xml',
    'tx_rector_pi1',
);

ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:examples/Configuration/Flexforms/HtmlParser.xml',
    $pluginSignature,
);

ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:examples/Configuration/Flexforms/HtmlParser.xml',
    'tx_rector_pi1',
);

?>

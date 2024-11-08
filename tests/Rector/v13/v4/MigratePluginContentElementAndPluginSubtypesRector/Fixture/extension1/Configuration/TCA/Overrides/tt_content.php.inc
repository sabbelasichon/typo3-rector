<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v4\MigratePluginContentElementAndPluginSubtypesRector\Fixture;

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

ExtensionManagementUtility::addPlugin(
    [
        'title', // Title
        'signature', // Signature
        'icon', // Icon
        'group', // Group
    ],
    'list_type',
    'extension_key'
);

ExtensionManagementUtility::addPlugin(
    [
        'title', // Title
        'signature', // Signature
        'icon', // Icon
        'group', // Group
    ],
    ExtensionUtility::PLUGIN_TYPE_PLUGIN,
    'extension_key'
);

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v4\MigratePluginContentElementAndPluginSubtypesRector\Fixture;

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

ExtensionManagementUtility::addPlugin(
    [
        'title', // Title
        'signature', // Signature
        'icon', // Icon
        'group', // Group
    ],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT,
    'extension_key'
);

ExtensionManagementUtility::addPlugin(
    [
        'title', // Title
        'signature', // Signature
        'icon', // Icon
        'group', // Group
    ],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT,
    'extension_key'
);

?>

<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v2\MigrateFrameModuleToSvgTreeRector\Fixture;

use \TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
ExtensionManagementUtility::addModule(
    'file',
    '',
    '',
    null,
    [
        'labels' => 'LLL:EXT:core/Resources/Private/Language/locallang_mod_file.xlf',
        'navigationFrameModule' => 'file_navframe',
        'name' => 'file',
        'workspaces' => 'online,custom',
        'iconIdentifier' => 'modulegroup-file'
    ]
);

use \TYPO3\CMS\Extbase\Utility\ExtensionUtility;
ExtensionUtility::registerModule(
    'file',
    '',
    '',
    null,
    [],
    [
        'labels' => 'LLL:EXT:core/Resources/Private/Language/locallang_mod_file.xlf',
        'navigationFrameModule' => 'file_navframe',
        'name' => 'file',
        'workspaces' => 'online,custom',
        'iconIdentifier' => 'modulegroup-file'
    ]
);

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v2\MigrateFrameModuleToSvgTreeRector\Fixture;

use \TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
ExtensionManagementUtility::addModule(
    'file',
    '',
    '',
    null,
    [
        'labels' => 'LLL:EXT:core/Resources/Private/Language/locallang_mod_file.xlf',
        'navigationComponentId' => 'TYPO3/CMS/Backend/Tree/FileStorageTreeContainer',
        'name' => 'file',
        'workspaces' => 'online,custom',
        'iconIdentifier' => 'modulegroup-file'
    ]
);

use \TYPO3\CMS\Extbase\Utility\ExtensionUtility;
ExtensionUtility::registerModule(
    'file',
    '',
    '',
    null,
    [],
    [
        'labels' => 'LLL:EXT:core/Resources/Private/Language/locallang_mod_file.xlf',
        'navigationComponentId' => 'TYPO3/CMS/Backend/Tree/FileStorageTreeContainer',
        'name' => 'file',
        'workspaces' => 'online,custom',
        'iconIdentifier' => 'modulegroup-file'
    ]
);

?>

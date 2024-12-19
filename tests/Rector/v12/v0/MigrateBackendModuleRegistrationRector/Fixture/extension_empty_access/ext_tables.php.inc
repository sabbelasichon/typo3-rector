<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\MigrateBackendModuleRegistrationRector\Fixture;

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
    'Extkey',
    'web',
    'example',
    'after:info',
    [
        'Vendor\Extension\Controller\MyExtbaseExampleModuleController::class' => 'list, detail',
    ],
    [
        'labels' => 'LLL:EXT:extkey/Resources/Private/Language/locallang_mod.xlf',
    ]
);
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\MigrateBackendModuleRegistrationRector\Fixture;

?>

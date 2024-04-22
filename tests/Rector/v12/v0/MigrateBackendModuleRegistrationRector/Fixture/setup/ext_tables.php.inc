<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\MigrateBackendModuleRegistrationRector\Fixture;

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addModule(
    'user',
    'setup',
    'bottom',
    '',
    [
        'routeTarget' => 'TYPO3\CMS\Setup\Controller\SetupModuleController::mainAction',
        'access' => 'group,user',
        'name' => 'user_setup',
        'iconIdentifier' => 'module-setup',
        'labels' => 'LLL:EXT:setup/Resources/Private/Language/locallang_mod.xlf',
    ]
);
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\MigrateBackendModuleRegistrationRector\Fixture;

?>

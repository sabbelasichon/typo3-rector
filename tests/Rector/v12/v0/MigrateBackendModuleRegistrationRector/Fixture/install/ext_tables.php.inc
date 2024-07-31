<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\MigrateBackendModuleRegistrationRector\Fixture;

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addModule(
    'tools',
    'toolsmaintenance',
    '',
    '',
    [
        'routeTarget' => 'TYPO3\CMS\Install\Controller\BackendModuleController::maintenanceAction',
        'access' => 'systemMaintainer',
        'name' => 'tools_toolsmaintenance',
        'iconIdentifier' => 'module-install-maintenance',
        'labels' => 'LLL:EXT:install/Resources/Private/Language/ModuleInstallMaintenance.xlf',
    ]
);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addModule(
    'tools',
    'toolssettings',
    '',
    '',
    [
        'routeTarget' => 'TYPO3\CMS\Install\Controller\BackendModuleController::settingsAction',
        'access' => 'systemMaintainer',
        'name' => 'tools_toolssettings',
        'iconIdentifier' => 'module-install-settings',
        'labels' => 'LLL:EXT:install/Resources/Private/Language/ModuleInstallSettings.xlf',
    ]
);
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\MigrateBackendModuleRegistrationRector\Fixture;

?>

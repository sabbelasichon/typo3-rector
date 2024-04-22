<?php

return [
    'tools_toolsmaintenance' => [
        'parent' => 'tools',
        'access' => 'systemMaintainer',
        'path' => '/module/tools/toolsmaintenance',
        'iconIdentifier' => 'module-install-maintenance',
        'labels' => 'LLL:EXT:install/Resources/Private/Language/ModuleInstallMaintenance.xlf',
        'routes' => [
            '_default' => [
                'target' => 'TYPO3\\CMS\\Install\\Controller\\BackendModuleController::maintenanceAction',
            ],
        ],
    ], 'tools_toolssettings' => [
        'parent' => 'tools',
        'access' => 'systemMaintainer',
        'path' => '/module/tools/toolssettings',
        'iconIdentifier' => 'module-install-settings',
        'labels' => 'LLL:EXT:install/Resources/Private/Language/ModuleInstallSettings.xlf',
        'routes' => [
            '_default' => [
                'target' => 'TYPO3\\CMS\\Install\\Controller\\BackendModuleController::settingsAction',
            ],
        ],
    ],
];

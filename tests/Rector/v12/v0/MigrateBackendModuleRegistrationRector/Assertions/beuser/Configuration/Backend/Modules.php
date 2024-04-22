<?php

return [
    'system_BeuserTxBeuser' => [
        'parent' => 'system',
        'position' => 'top',
        'access' => 'admin',
        'iconIdentifier' => 'module-beuser',
        'labels' => 'LLL:EXT:beuser/Resources/Private/Language/locallang_mod.xlf',
        'extensionName' => 'Beuser',
        'controllerActions' => [
            'TYPO3\CMS\Beuser\Controller\BackendUserController' => [
                'index',
                'show',
                'addToCompareList',
                'removeFromCompareList',
                'removeAllFromCompareList',
                'compare',
                'online',
                'terminateBackendUserSession',
                'initiatePasswordReset',
                'groups',
                'addGroupToCompareList',
                'removeGroupFromCompareList',
                'removeAllGroupsFromCompareList',
                'compareGroups',
            ],
        ],
    ], 'system_BeuserTxPermission' => [
        'parent' => 'system',
        'position' => 'top',
        'access' => 'admin',
        'path' => '/module/system/beusertxpermission',
        'iconIdentifier' => 'module-permission',
        'navigationComponent' => 'TYPO3/CMS/Backend/PageTree/PageTreeElement',
        'labels' => 'LLL:EXT:beuser/Resources/Private/Language/locallang_mod_permission.xlf',
        'routes' => [
            '_default' => [
                'target' => 'TYPO3\\CMS\\Beuser\\Controller\\PermissionController::handleRequest',
            ],
        ],
    ],
];

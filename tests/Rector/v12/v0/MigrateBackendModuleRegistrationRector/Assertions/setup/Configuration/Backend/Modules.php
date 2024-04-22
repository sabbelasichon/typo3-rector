<?php

return [
    'user_setup' => [
        'parent' => 'user',
        'access' => 'user',
        'path' => '/module/user/setup',
        'iconIdentifier' => 'module-setup',
        'labels' => 'LLL:EXT:setup/Resources/Private/Language/locallang_mod.xlf',
        'routes' => [
            '_default' => [
                'target' => 'TYPO3\\CMS\\Setup\\Controller\\SetupModuleController::mainAction',
            ],
        ],
    ],
];

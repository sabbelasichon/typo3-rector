<?php

return [
    'web_ViewpageView' => [
        'parent' => 'web',
        'position' => [
            'after' => 'web_layout',
        ],
        'access' => 'user',
        'path' => '/module/web/viewpageview',
        'iconIdentifier' => 'module-viewpage',
        'labels' => 'LLL:EXT:viewpage/Resources/Private/Language/locallang_mod.xlf',
        'routes' => [
            '_default' => [
                'target' => 'TYPO3\\CMS\\Viewpage\\Controller\\ViewModuleController::handleRequest',
            ],
        ],
    ],
];

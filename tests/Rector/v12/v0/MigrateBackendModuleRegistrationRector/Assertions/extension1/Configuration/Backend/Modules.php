<?php

return [
    'web_example' => [
        'parent' => 'web',
        'position' => 'top',
        'access' => 'admin',
        'workspaces' => 'live',
        'path' => '/module/web/example',
        'iconIdentifier' => 'module-example',
        'navigationComponent' => 'TYPO3/CMS/Backend/PageTree/PageTreeElement',
        'labels' => 'LLL:EXT:example/Resources/Private/Language/locallang_mod.xlf',
        'routes' => [
            '_default' => [
                'target' => 'Vendor\\Extension\\Controller\\MyExampleModuleController::handleRequest',
            ],
        ],
    ],
];

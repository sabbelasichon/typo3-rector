<?php

return [
    'myextension' => [
        'labels' => 'LLL:EXT:my_extension/Resources/Private/Language/locallang_mod_web.xlf',
        'iconIdentifier' => 'modulegroup-myextension',
        'navigationComponent' => '@typo3/backend/page-tree/page-tree-element',
        'controllerActions' => [
            'ExampleController' => [
                'list',
                'detail',
            ],
        ],
    ],
];
?>
-----
<?php

return [
    'myextension' => [
        'labels' => 'LLL:EXT:my_extension/Resources/Private/Language/locallang_mod_web.xlf',
        'iconIdentifier' => 'modulegroup-myextension',
        'navigationComponent' => '@typo3/backend/tree/page-tree-element',
        'controllerActions' => [
            'ExampleController' => [
                'list',
                'detail',
            ],
        ],
    ],
];
?>

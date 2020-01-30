<?php

return [
    'ctrl' => [
    ],
    'columns' => [
        'sys_language_uid' => [
            'config' => [
                'type' => 'select',
            ],
        ],
        'rte_enabled' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.php:LGL.disableRTE',
            'config' => [
                'type' => 'check',
                'showIfRTE' => 1,
            ],
        ],
        'positive' => [
            'config' => [
                'type' => 'select',
                'renderType' => 'selectTree',
                'autoSizeMax' => 5,
            ],
        ],
    ],
];

?>
-----
<?php

return ['ctrl' => [], 'columns' => ['sys_language_uid' => ['config' => ['type' => 'select', 'renderType' => 'selectSingle']], 'rte_enabled' => ['exclude' => 1, 'label' => 'LLL:EXT:lang/locallang_general.php:LGL.disableRTE', 'config' => ['type' => 'check']], 'positive' => ['config' => ['type' => 'select', 'renderType' => 'selectTree', 'size' => 5]]]];

?>

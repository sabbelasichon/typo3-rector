<?php

return [
    'ctrl' => [
    ],
    'columns' => [
        'sys_language_uid' => [
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'selicon_cols' => 16,
                'noIconsBelowSelect' => false,
                'suppress_icons' => 'IF_VALUE_FALSE',
                'foreign_table_loadIcons' => true,
            ],
        ],
        'foo' => [
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'noIconsBelowSelect' => false,
            ],
        ],
    ],
];

?>
-----
<?php

return [
    'ctrl' => [
    ],
    'columns' => [
        'sys_language_uid' => [
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'selicon_cols' => 16,
                'showIconTable' => true,
            ],
        ],
        'foo' => [
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'showIconTable' => true,
            ],
        ],
    ],
];

?>

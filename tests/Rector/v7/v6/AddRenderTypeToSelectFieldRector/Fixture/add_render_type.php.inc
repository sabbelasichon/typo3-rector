<?php

return [
    'ctrl' => [
    ],
    'columns' => [
        'sys_language_uid' => [
            'config' => [
                'type' => 'select',
                'maxitems' => 1,
            ],
        ],
        'positive' => [
            'config' => [
                'type' => 'select',
                'maxitems' => 5,
            ],
        ],
        'foo' => [
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
            ],
        ],
        'bar' => [
            'config' => [
                'type' => 'select',
                'renderMode' => 'tree',
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
                'maxitems' => 1,
                'renderType' => 'selectSingle',
            ],
        ],
        'positive' => [
            'config' => [
                'type' => 'select',
                'maxitems' => 5,
                'renderType' => 'selectMultipleSideBySide',
            ],
        ],
        'foo' => [
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
            ],
        ],
        'bar' => [
            'config' => [
                'type' => 'select',
                'renderMode' => 'tree',
                'renderType' => 'selectTree',
            ],
        ],
    ],
];

?>

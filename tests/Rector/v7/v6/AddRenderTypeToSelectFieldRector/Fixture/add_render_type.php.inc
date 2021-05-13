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
                'renderType' => 'selectSingle',
                'maxitems' => 1,
            ],
        ],
        'positive' => [
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
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
                'renderType' => 'selectTree',
                'renderMode' => 'tree',
            ],
        ],
    ],
];

?>

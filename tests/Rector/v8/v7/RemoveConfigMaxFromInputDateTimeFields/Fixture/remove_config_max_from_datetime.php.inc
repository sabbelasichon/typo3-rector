<?php

return [
    'ctrl' => [
    ],
    'columns' => [
        't3ver_label' => [
            'label' => 'Versioning',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255,
            ],
        ],
        'date' => [
            'exclude' => false,
            'label' => 'Date',
            'config' => [
                'dbType' => 'date',
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'max' => 1,
                'size' => 7,
                'eval' => 'date',
                'default' => null,
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
        't3ver_label' => [
            'label' => 'Versioning',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255,
            ],
        ],
        'date' => [
            'exclude' => false,
            'label' => 'Date',
            'config' => [
                'dbType' => 'date',
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 7,
                'eval' => 'date',
                'default' => null,
            ],
        ],
    ],
];

?>

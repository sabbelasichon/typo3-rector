<?php

return [
    'ctrl' => [
    ],
    'columns' => [
        'input_30' => [
            'label' => 'input_30',
            'config' => [
                'type' => 'input',
                'size' => 5,
                'eval' => 'trim,int',
                'range' => [
                    'lower' => -90,
                    'upper' => 90,
                ],
                'default' => 0,
                'wizards' => [
                    'angle' => [
                        'type' => 'slider',
                        'step' => 10,
                        'width' => 200,
                    ],
                ],
            ],
        ],
        'input_31' => [
            'label' => 'input_30',
            'config' => [
                'type' => 'input',
                'size' => 5,
                'eval' => 'trim,int',
                'range' => [
                    'lower' => -90,
                    'upper' => 90,
                ],
                'default' => 0,
                'slider' => ['step' => 10, 'width' => 200],
                'wizards' => [
                    'angle' => [
                        'type' => 'slider',
                        'step' => 10,
                        'width' => 200,
                    ],
                ],
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
        'input_30' => [
            'label' => 'input_30',
            'config' => [
                'type' => 'input',
                'size' => 5,
                'eval' => 'trim,int',
                'range' => [
                    'lower' => -90,
                    'upper' => 90,
                ],
                'default' => 0,
                'slider' => ['step' => 10, 'width' => 200],
            ],
        ],
        'input_31' => [
            'label' => 'input_30',
            'config' => [
                'type' => 'input',
                'size' => 5,
                'eval' => 'trim,int',
                'range' => [
                    'lower' => -90,
                    'upper' => 90,
                ],
                'default' => 0,
                'slider' => ['step' => 10, 'width' => 200],
            ],
        ],
    ],
];

?>

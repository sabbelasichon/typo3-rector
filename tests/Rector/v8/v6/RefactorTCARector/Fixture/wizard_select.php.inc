<?php

return [
    'ctrl' => [
    ],
    'columns' => [
        'input_33' => [
            'label' => 'input_33',
            'config' => [
                'type' => 'input',
                'wizards' => [
                    'select' => [
                        'items' => [
                            ['spring', 'Spring'],
                            ['summer', 'Summer'],
                            ['autumn', 'Autumn'],
                            ['winter', 'Winter'],
                        ],
                    ],
                ],
            ],
        ],
        'input_34' => [
            'label' => 'input_33',
            'config' => [
                'type' => 'input',
                'valuePicker' => ['items' => [['spring', 'Spring'], ['summer', 'Summer'], ['autumn', 'Autumn'], ['winter', 'Winter']]],
                'wizards' => [
                    'select' => [
                        'items' => [
                            ['spring', 'Spring'],
                            ['summer', 'Summer'],
                            ['autumn', 'Autumn'],
                            ['winter', 'Winter'],
                        ],
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
        'input_33' => [
            'label' => 'input_33',
            'config' => [
                'type' => 'input',
                'valuePicker' => ['items' => [['spring', 'Spring'], ['summer', 'Summer'], ['autumn', 'Autumn'], ['winter', 'Winter']]],
            ],
        ],
        'input_34' => [
            'label' => 'input_33',
            'config' => [
                'type' => 'input',
                'valuePicker' => ['items' => [['spring', 'Spring'], ['summer', 'Summer'], ['autumn', 'Autumn'], ['winter', 'Winter']]],
            ],
        ],
    ],
];

?>

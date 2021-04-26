<?php

return [
    'ctrl' => [
    ],
    'columns' => [
        'foo' => [
            'label' => 'foo',
            'config' => [
                'type' => 'input',
                'eval' => 'date',
            ],
        ],
        'bar' => [
            'label' => 'foo',
            'config' => [
                'type' => 'input',
                'eval' => 'datetime',
            ],
        ],
        'baz' => [
            'label' => 'foo',
            'config' => [
                'type' => 'input',
                'eval' => 'time',
            ],
        ],
        'biz' => [
            'label' => 'foo',
            'config' => [
                'type' => 'input',
                'eval' => 'timesec',
            ],
        ],
        'fizz' => [
            'label' => 'foo',
            'config' => [
                'type' => 'input',
                'eval' => 'timesec',
                'renderType' => 'inputDateTime',
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
        'foo' => [
            'label' => 'foo',
            'config' => [
                'type' => 'input',
                'eval' => 'date',
                'renderType' => 'inputDateTime',
            ],
        ],
        'bar' => [
            'label' => 'foo',
            'config' => [
                'type' => 'input',
                'eval' => 'datetime',
                'renderType' => 'inputDateTime',
            ],
        ],
        'baz' => [
            'label' => 'foo',
            'config' => [
                'type' => 'input',
                'eval' => 'time',
                'renderType' => 'inputDateTime',
            ],
        ],
        'biz' => [
            'label' => 'foo',
            'config' => [
                'type' => 'input',
                'eval' => 'timesec',
                'renderType' => 'inputDateTime',
            ],
        ],
        'fizz' => [
            'label' => 'foo',
            'config' => [
                'type' => 'input',
                'eval' => 'timesec',
                'renderType' => 'inputDateTime',
            ],
        ],
    ],
];

?>

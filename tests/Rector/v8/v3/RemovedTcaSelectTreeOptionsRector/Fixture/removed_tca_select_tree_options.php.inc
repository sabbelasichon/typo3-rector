<?php

return [
    'ctrl' => [
    ],
    'columns' => [
        'categories' => [
            'config' => [
                'type' => 'input',
                'renderType' => 'selectTree',
                'autoSizeMax' => 5,
                'treeConfig' => [
                    'appearance' => [
                        'width' => 100,
                        'allowRecursiveMode' => true
                    ]
                ]
            ],
        ],
        'foo' => [
            'config' => [
                'type' => 'input',
                'autoSizeMax' => 5,
                'treeConfig' => [
                    'appearance' => [
                        'width' => 100,
                        'allowRecursiveMode' => true
                    ]
                ]
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
        'categories' => [
            'config' => [
                'type' => 'input',
                'renderType' => 'selectTree',
                'size' => 5,
                'treeConfig' => [
                    'appearance' => []
                ]
            ],
        ],
        'foo' => [
            'config' => [
                'type' => 'input',
                'autoSizeMax' => 5,
                'treeConfig' => [
                    'appearance' => [
                        'width' => 100,
                        'allowRecursiveMode' => true
                    ]
                ]
            ],
        ],
    ],
];

?>

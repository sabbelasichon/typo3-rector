<?php

return [
    'ctrl' => [
    ],
    'columns' => [
        'foobar' => [
            'exclude' => 0,
            'label' => 'FoobarLabel',
            'config' => [
                'type' => 'text',
                'cols' => 48,
                'rows' => 20,
                'wizards' => [
                    '_PADDING' => 1,
                    '_VERTICAL' => 1,
                    'fooWizard' => [
                        'type' => 'userFunc',
                        'userFunc' => 'FooBarBaz->render',
                    ],
                ],
            ]
        ],
        'group_db_8' => [
            'label' => 'group_db_8',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'tx_styleguide_staticdata',
                'wizards' => [
                    '_POSITION' => 'top',
                    'suggest' => [
                        'type' => 'suggest',
                        'default' => [
                            'pidList' => 42,
                        ],
                    ],
                ],
            ],
        ],
        'random_one' => [
            'exclude' => 1,
            'label' => 'RandomOne',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'pages',
                'size' => 1,
                'minitems' => 0,
                'maxitems' => 1,
                'show_thumbs' => '1',
                'appearance' => [
                    'elementBrowserType' => 'db'
                ],
                'wizards' => [
                    '_PADDING' => 1,
                    'suggest' => [
                        'type' => 'suggest',
                        'pages' => [
                            'searchCondition' => 'doktype=22',
                        ],
                    ],
                ],
            ],
        ],
        'random_two' => [
            'exclude' => 1,
            'label' => 'RandomTwo',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'pages',
                'size' => 1,
                'minitems' => 0,
                'maxitems' => 1,
                'show_thumbs' => '1',
                'appearance' => [
                    'elementBrowserType' => 'db'
                ],
                'wizards' => [
                    'suggest' => [
                        'type' => 'suggest',
                        'default' => [
                            'searchWholePhrase' => 1
                        ],
                        'pages' => [
                            'searchCondition' => 'doktype = 1'
                        ]
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
        'foobar' => [
            'exclude' => 0,
            'label' => 'FoobarLabel',
            'config' => [
                'type' => 'text',
                'cols' => 48,
                'rows' => 20,
                'wizards' => [
                    '_PADDING' => 1,
                    '_VERTICAL' => 1,
                    'fooWizard' => [
                        'type' => 'userFunc',
                        'userFunc' => 'FooBarBaz->render',
                    ],
                ],
            ]
        ],
        'group_db_8' => [
            'label' => 'group_db_8',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'tx_styleguide_staticdata',
                'suggestOptions' => [
                    'default' => [
                        'pidList' => 42,
                    ],
                ],
            ],
        ],
        'random_one' => [
            'exclude' => 1,
            'label' => 'RandomOne',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'pages',
                'size' => 1,
                'minitems' => 0,
                'maxitems' => 1,
                'show_thumbs' => '1',
                'appearance' => [
                    'elementBrowserType' => 'db'
                ],
                'suggestOptions' => [
                    'pages' => [
                        'searchCondition' => 'doktype=22',
                    ],
                ],
            ],
        ],
        'random_two' => [
            'exclude' => 1,
            'label' => 'RandomTwo',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'pages',
                'size' => 1,
                'minitems' => 0,
                'maxitems' => 1,
                'show_thumbs' => '1',
                'appearance' => [
                    'elementBrowserType' => 'db'
                ],
                'suggestOptions' => [
                    'default' => [
                        'searchWholePhrase' => 1
                    ],
                    'pages' => [
                        'searchCondition' => 'doktype = 1'
                    ]
                ]
            ],
        ],
    ],
];

?>

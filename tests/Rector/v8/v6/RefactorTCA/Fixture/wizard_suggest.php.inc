<?php

return [
    'ctrl' => [
    ],
    'columns' => [
        'mail_message' => [
            'exclude' => 0,
            'label' => 'Nachricht',
            'config' => [
                'type' => 'text',
                'cols' => 48,
                'rows' => 20,
                'wizards' => [
                    '_PADDING' => 1,
                    '_VERTICAL' => 1,
                    'feedbackLink' => [
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
        'name_of_travel_guide' => [
            'exclude' => 1,
            'label' => 'Name des Reiseleiters',
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
    ],
];

?>
-----
<?php

return [
    'ctrl' => [
    ],
    'columns' => [
        'mail_message' => [
            'exclude' => 0,
            'label' => 'Nachricht',
            'config' => [
                'type' => 'text',
                'cols' => 48,
                'rows' => 20,
                'wizards' => [
                    '_PADDING' => 1,
                    '_VERTICAL' => 1,
                    'feedbackLink' => [
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
                'suggestOptions' => ['default' => [
                    'pidList' => 42,
                ]],
            ],
        ],
        'name_of_travel_guide' => [
            'exclude' => 1,
            'label' => 'Name des Reiseleiters',
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
                'suggestOptions' => ['pages' => [
                    'searchCondition' => 'doktype=22',
                ]],
            ],
        ],
    ],
];

?>

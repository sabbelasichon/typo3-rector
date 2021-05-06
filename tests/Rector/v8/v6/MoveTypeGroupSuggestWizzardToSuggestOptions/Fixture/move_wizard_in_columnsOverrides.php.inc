<?php

return [
    'ctrl' => [
    ],
    'types' => [
        'someType' => [
            'showitem' => 'hidden, myText',
            'columnsOverrides' => [
                'group_db_8' => [
                    'config' => [
                        'wizards' => [ //Todo: remove empty sub-array after refactoring
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
                'other' => [
                    'config' => [
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
            ],
        ],
    ],
    'columns' => [
        'group_db_8' => [
            'label' => 'group_db_8',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'tx_styleguide_staticdata',
            ],
        ],
        'other' => [
            'label' => 'other',
            'config' => [
                'type' => 'other',
                'internal_type' => 'db',
                'allowed' => 'tx_styleguide_staticdata',
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
    'types' => [
        'someType' => [
            'showitem' => 'hidden, myText',
            'columnsOverrides' => [
                'group_db_8' => [
                    'config' => [
                        'wizards' => [ //Todo: remove empty sub-array after refactoring
                            '_POSITION' => 'top',
                        ], 'suggestOptions' => [
                            'default' => [
                                'pidList' => 42,
                            ],
                        ],
                    ],
                ],
                'other' => [
                    'config' => [
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
            ],
        ],
    ],
    'columns' => [
        'group_db_8' => [
            'label' => 'group_db_8',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'tx_styleguide_staticdata',
            ],
        ],
        'other' => [
            'label' => 'other',
            'config' => [
                'type' => 'other',
                'internal_type' => 'db',
                'allowed' => 'tx_styleguide_staticdata',
            ],
        ],
    ],
];

?>

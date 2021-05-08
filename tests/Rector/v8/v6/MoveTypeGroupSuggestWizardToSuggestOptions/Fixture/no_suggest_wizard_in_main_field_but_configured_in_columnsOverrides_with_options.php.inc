<?php

return [
    'ctrl' => [
    ],
    'columns' => [
        'aField' => [
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'aTable',
            ],
        ],
    ],
    'types' => [
        'aType' => [
            'columnsOverrides' => [
                'aField' => [
                    'config' => [
                        'wizards' => [
                            'suggest' => [
                                'type' => 'suggest',
                                'default' => [
                                    'minimumCharacters' => 23,
                                ],
                                'aTable' => [
                                    'searchCondition' => 'doktype = 1'
                                ],
                            ],
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
        'aField' => [
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'aTable',
                'hideSuggest' => true,
            ],
        ],
    ],
    'types' => [
        'aType' => [
            'columnsOverrides' => [
                'aField' => [
                    'config' => ['suggestOptions' => [
                        'default' => [
                            'minimumCharacters' => 23,
                        ],
                        'aTable' => [
                            'searchCondition' => 'doktype = 1'
                        ],
                    ], 'hideSuggest' => false],
                ],
            ],
        ],
    ],
];

?>

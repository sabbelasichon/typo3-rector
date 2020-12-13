<?php

return [
    'ctrl' => [
    ],
    'columns' => [
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
    ],
];

?>
-----
<?php

return [
    'ctrl' => [
    ],
    'columns' => [
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
    ],
];

?>

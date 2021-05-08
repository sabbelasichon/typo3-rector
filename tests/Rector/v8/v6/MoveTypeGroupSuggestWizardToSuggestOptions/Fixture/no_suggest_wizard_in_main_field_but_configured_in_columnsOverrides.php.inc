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
                    'config' => [
                        'hideSuggest' => false,
                    ],
                ],
            ],
        ],
    ],
];

?>

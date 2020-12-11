<?php

return [
    'ctrl' => [
    ],
    'columns' => [
        'aField' => [
            'config' => [
                'type' => 'inline',
                'foreign_types' => [
                    'aForeignType' => [
                        'showitem' => 'aChildField',
                        'some_more' => 'more',
                    ],
                ],
                'overrideChildTca' => [
                    'types' => [
                        'aExistingForeignType' => [
                            'showitem' => 'anOtherChildField',
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
                'type' => 'inline',
                'overrideChildTca' => [
                    'types' => [
                        'aExistingForeignType' => [
                            'showitem' => 'anOtherChildField',
                        ], 'aForeignType' => [
                            'showitem' => 'aChildField',
                            'some_more' => 'more',
                        ],
                    ],
                ],
            ],
        ],
    ],
];

?>

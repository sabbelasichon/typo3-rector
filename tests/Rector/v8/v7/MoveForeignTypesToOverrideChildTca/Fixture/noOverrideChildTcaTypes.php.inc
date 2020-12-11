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
                    ],
                ],
                'overrideChildTca' => [

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
                'overrideChildTca' => ['types' => [
                    'aForeignType' => [
                        'showitem' => 'aChildField',
                    ],
                ]],
            ],
        ],
    ],
];

?>

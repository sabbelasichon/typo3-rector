<?php
return [
'ctrl' => [
],
    'columns' => [
        'aField' => [
            'config' => [
                'type' => 'inline',
                'foreign_record_defaults' => [
                    'aChildField' => 42,
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
                'overrideChildTca' => ['columns' => ['aChildField' => ['config' => ['default' => 42]]]],
            ],
        ],
    ],
];
?>

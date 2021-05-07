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
                'wizards' => [
                    'suggest' => [
                        'type' => 'suggest',
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
            ],
        ],
    ],
];

?>

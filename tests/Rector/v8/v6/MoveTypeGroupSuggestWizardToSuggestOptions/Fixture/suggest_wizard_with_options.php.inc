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
                        'default' => [
                            'minimumCharacters' => 23,
                            'anOption' => 'anOptionValue',
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
                'suggestOptions' => [
                    'default' => [
                        'minimumCharacters' => 23,
                        'anOption' => 'anOptionValue',
                    ],
                ],
            ],
        ],
    ],
];

?>

<?php

return [
    'ctrl' => [
    ],
    'columns' => [
        'input_34' => [
            'label' => 'input_34',
            'config' => [
                'type' => 'input',
                'wizards' => [
                    'colorChoice' => [
                        'type' => 'colorbox',
                        'title' => 'LLL:EXT:examples/Resources/Private/Language/locallang_db.xlf:tx_examples_haiku.colorPick',
                        'module' => [
                            'name' => 'wizard_colorpicker',
                        ],
                        'JSopenParams' => 'height=600,width=380,status=0,menubar=0,scrollbars=1',
                        'exampleImg' => 'EXT:examples/res/images/japanese_garden.jpg',
                    ]
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
        'input_34' => [
            'label' => 'input_34',
            'config' => [
                'type' => 'input',
                'renderType' => 'colorpicker',
            ],
        ],
    ],
];

?>

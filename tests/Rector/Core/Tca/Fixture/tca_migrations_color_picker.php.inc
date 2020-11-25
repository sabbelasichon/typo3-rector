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
                    ],
                ],
            ],
        ],
        'text_17' => [
            'label' => 'text_17',
            'config' => [
                'type' => 'text',
                'cols' => '40',
                'rows' => '5',
                'wizards' => [
                    'table' => [
                        'notNewRecords' => 1,
                        'type' => 'script',
                        'title' => 'LLL:EXT:cms/locallang_ttc.xlf:bodytext.W.table',
                        'icon' => 'content-table',
                        'module' => [
                            'name' => 'wizard_table'
                        ],
                        'params' => [
                            'xmlOutput' => 0
                        ]
                    ],
                ],
            ],
        ],
    ],
];

?>
-----
<?php

return ['ctrl' => [], 'columns' => ['input_34' => ['label' => 'input_34', 'config' => ['type' => 'input', 'renderType' => 'colorpicker']], 'text_17' => ['label' => 'text_17', 'config' => ['type' => 'text', 'cols' => '40', 'rows' => '5', 'renderType' => 'textTable', 'fieldControl' => ['tableWizard' => ['options' => ['title' => 'LLL:EXT:cms/locallang_ttc.xlf:bodytext.W.table']]]]]]];

?>

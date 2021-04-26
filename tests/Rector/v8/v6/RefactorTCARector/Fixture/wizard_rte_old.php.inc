<?php

return [
    'ctrl' => [
    ],
    'columns' => [
        'rte_1' => [
            'label' => 'rte_1',
            'config' => [
                'type' => 'text',
                'cols' => '30',
                'rows' => '5',
                'wizards' => [
                    'RTE' => [
                        'icon' => 'wizard_rte2.gif',
                        'notNewRecords'=> 1,
                        'RTEonly' => 1,
                        'script' => 'wizard_rte.php',
                        'title' => 'LLL:EXT:cms/locallang_ttc.xlf:bodytext.W.RTE',
                        'type' => 'script'
                    ]
                ]
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
        'rte_1' => [
            'label' => 'rte_1',
            'config' => [
                'type' => 'text',
                'cols' => '30',
                'rows' => '5',
                'fieldControl' => ['fullScreenRichtext' => ['disabled' => false, 'options' => ['title' => 'LLL:EXT:cms/locallang_ttc.xlf:bodytext.W.RTE']]]
            ],
        ],
    ],
];

?>

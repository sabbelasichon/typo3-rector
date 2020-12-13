<?php

return [
    'ctrl' => [
    ],
    'columns' => [
        'rte_1' => [
            'label' => 'rte_1',
            'config' => [
                'type' => 'text',
                'enableRichtext' => true,
                'RTE' => [
                    'notNewRecords' => 1,
                    'RTEonly' => 1,
                    'type' => 'script',
                    'title' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:bodytext.W.RTE',
                    'icon' => 'actions-wizard-rte',
                    'module' => [
                        'name' => 'wizard_rte'
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
        'rte_1' => [
            'label' => 'rte_1',
            'config' => [
                'type' => 'text',
                'enableRichtext' => true,
                'fieldControl' => ['fullScreenRichtext' => ['disabled' => false]],
            ],
        ],
    ],
];

?>

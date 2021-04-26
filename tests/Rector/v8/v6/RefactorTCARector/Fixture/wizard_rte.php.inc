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
                    '_PADDING' => 2,
                    'RTE' => [
                        'notNewRecords' => 1,
                        'RTEonly' => 1,
                        'title' => 'Full screen Rich Text Editing|Volltextansicht',
                        'icon' => 'EXT:backend/Resources/Public/Images/FormFieldWizard/wizard_rte.gif',
                        'module' => [
                            'name' => 'wizard_rte',
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
        'rte_1' => [
            'label' => 'rte_1',
            'config' => [
                'type' => 'text',
                'cols' => '30',
                'rows' => '5',
                'fieldControl' => ['fullScreenRichtext' => ['disabled' => false, 'options' => ['title' => 'Full screen Rich Text Editing|Volltextansicht']]],
            ],
        ],
    ],
];

?>

<?php

return [
    'ctrl' => [
    ],
    'columns' => [
        'description' => [
            'exclude' => false,
            'label' => 'LLL:EXT:gs1_vacancies/Resources/Private/Language/locallang_db.xlf:tx_gs1vacancies_domain_model_vacancy.description',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim,required',
            ],
            'defaultExtras' => 'richtext:rte_transform[mode=ts_css]',
        ],
    ],
    'types' => [
        'myType' => [
            'columnsOverrides' => [
                'aField' => [
                    'defaultExtras' => 'richtext:rte_transform',
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
        'description' => [
            'exclude' => false,
            'label' => 'LLL:EXT:gs1_vacancies/Resources/Private/Language/locallang_db.xlf:tx_gs1vacancies_domain_model_vacancy.description',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim,required',
                'enableRichtext' => true,
                'richtextConfiguration' => 'default',
            ],
        ],
    ],
    'types' => [
        'myType' => [
            'columnsOverrides' => [
                'aField' => [
                    'config' => ['enableRichtext' => true, 'richtextConfiguration' => 'default'],
                ],
            ],
        ],
    ],
];

?>

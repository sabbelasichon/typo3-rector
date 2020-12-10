<?php

return [
    'ctrl' => [
    ],
    'columns' => [
        'starttime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:starttime_formlabel',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 16,
                'eval' => 'datetime,int',
                'default' => 0,
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],
        'foo' => [
            'label' => 'Bar',
            'config' => [
                'type' => 'inline',
                'behaviour' => [
                    'localizationMode' => 'keep',
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],
        'bar' => [
            'label' => 'Bar',
            'config' => [
                'type' => 'inline',
                'behaviour' => [
                    'localizationMode' => 'foo',
                    'allowLanguageSynchronization' => true,
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
        'starttime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:starttime_formlabel',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 16,
                'eval' => 'datetime,int',
                'default' => 0,
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],
        'foo' => [
            'label' => 'Bar',
            'config' => [
                'type' => 'inline',
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],
        'bar' => [
            'label' => 'Bar',
            'config' => [
                'type' => 'inline',
                'behaviour' => [
                    'localizationMode' => 'foo',
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],
    ],
];

?>

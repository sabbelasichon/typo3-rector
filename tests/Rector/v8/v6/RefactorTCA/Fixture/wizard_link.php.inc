<?php

return [
    'ctrl' => [
    ],
    'columns' => [
        'input_29' => [
            'label' => 'input_29 link',
            'config' => [
                'type' => 'input',
                'wizards' => [
                    'link' => [
                        'type' => 'popup',
                        'title' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:header_link_formlabel',
                        'icon' => 'actions-wizard-link',
                        'module' => [
                            'name' => 'wizard_link',
                        ],
                        'JSopenParams' => 'height=800,width=600,status=0,menubar=0,scrollbars=1',
                        'params' => [
                            'blindLinkOptions' => 'folder',
                            'blindLinkFields' => 'class, target',
                            'allowedExtensions' => 'jpg',
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
        'input_29' => [
            'label' => 'input_29 link',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputLink',
                'fieldControl' => ['linkPopup' => ['options' => ['title' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:header_link_formlabel', 'blindLinkOptions' => 'folder', 'blindLinkFields' => 'class, target', 'allowedExtensions' => 'jpg']]],
            ],
        ],
    ],
];

?>

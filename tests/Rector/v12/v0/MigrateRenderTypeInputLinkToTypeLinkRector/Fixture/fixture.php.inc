<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\MigrateRenderTypeInputLinkToTypeLinkRector\Fixture;

return [
    'ctrl' => [],
    'columns' => [
        'full_example' => [
            'config' => [
                'type' => 'input',
                'renderType' => 'inputLink',
                'required' => true,
                'size' => 21,
                'max' => 1234,
                'eval' => 'trim,null',
                'fieldControl' => [
                    'linkPopup' => [
                        'disabled' => true,
                        'options' => [
                            'title' => 'Browser title',
                            'allowedExtensions' => 'jpg,png',
                            'blindLinkFields' => 'class,target,title',
                            'blindLinkOptions' => 'mail,folder,file,telephone',
                        ],
                    ],
                ],
                'softref' => 'typolink',
            ],
        ],
        'migrate_type_and_remove_eval' => [
            'config' => [
                'type' => 'input',
                'renderType' => 'inputLink',
                'eval' => 'trim',
            ],
        ],
        'full_blind_options' => [
            'config' => [
                'type' => 'input',
                'renderType' => 'inputLink',
                'fieldControl' => [
                    'linkPopup' => [
                        'options' => [
                            'blindLinkOptions' => 'page,file,folder,url,mail,record,telephone',
                            'blindLinkFields' => 'class,target,title,params,rel',
                        ],
                    ],
                ],
            ],
        ],
        'empty_blind_options' => [
            'config' => [
                'type' => 'input',
                'renderType' => 'inputLink',
                'fieldControl' => [
                    'linkPopup' => [
                        'disabled' => false,
                        'options' => [
                            'title' => '',
                            'blindLinkOptions' => '',
                            'blindLinkFields' => '',
                            'allowedExtensions' => '',
                        ],
                    ],
                ],
            ],
        ],
        'non_empty_fieldcontrol_is_kept' => [
            'config' => [
                'type' => 'input',
                'renderType' => 'inputLink',
                'eval' => 'trim',
                'fieldControl' => [
                    'linkPopup' => [
                        'disabled' => true,
                    ],
                    'editPopup' => [
                        'disabled' => false,
                    ],
                ],
            ],
        ],
        'ensure_email_is_used_as_term' => [
            'config' => [
                'type' => 'input',
                'renderType' => 'inputLink',
                'fieldControl' => [
                    'linkPopup' => [
                        'options' => [
                            'blindLinkOptions' => 'page,file,folder,url,telephone',
                        ],
                    ],
                ],
            ],
        ],
        'already_migrated_full_example' => [
            'config' => [
                'type' => 'link',
                'required' => true,
                'size' => 21,
                'eval' => 'null',
                'allowedTypes' => ['page', 'url', 'record'],
                'appearance' => [
                    'enableBrowser' => false,
                    'browserTitle' => 'Browser title',
                    'allowedOptions' => ['params', 'rel'],
                    'allowedFileExtensions' => ['jpg', 'png']
                ],
            ],
        ],
    ],
];

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\MigrateRenderTypeInputLinkToTypeLinkRector\Fixture;

return [
    'ctrl' => [],
    'columns' => [
        'full_example' => [
            'config' => [
                'type' => 'link',
                'required' => true,
                'size' => 21,
                'eval' => 'null',
                'allowedTypes' => ['page', 'url', 'record'],
                'appearance' => ['enableBrowser' => false, 'browserTitle' => 'Browser title', 'allowedOptions' => ['params', 'rel'], 'allowedFileExtensions' => ['jpg', 'png']],
            ],
        ],
        'migrate_type_and_remove_eval' => [
            'config' => [
                'type' => 'link',
            ],
        ],
        'full_blind_options' => [
            'config' => [
                'type' => 'link',
                'allowedTypes' => [],
                'appearance' => ['allowedOptions' => []],
            ],
        ],
        'empty_blind_options' => [
            'config' => [
                'type' => 'link',
            ],
        ],
        'non_empty_fieldcontrol_is_kept' => [
            'config' => [
                'type' => 'link',
                'fieldControl' => [
                    'editPopup' => [
                        'disabled' => false,
                    ],
                ],
                'appearance' => ['enableBrowser' => false],
            ],
        ],
        'ensure_email_is_used_as_term' => [
            'config' => [
                'type' => 'link',
                'allowedTypes' => ['email', 'record'],
            ],
        ],
        'already_migrated_full_example' => [
            'config' => [
                'type' => 'link',
                'required' => true,
                'size' => 21,
                'eval' => 'null',
                'allowedTypes' => ['page', 'url', 'record'],
                'appearance' => [
                    'enableBrowser' => false,
                    'browserTitle' => 'Browser title',
                    'allowedOptions' => ['params', 'rel'],
                    'allowedFileExtensions' => ['jpg', 'png']
                ],
            ],
        ],
    ],
];

?>

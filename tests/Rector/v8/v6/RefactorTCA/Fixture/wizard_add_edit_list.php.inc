<?php

return [
    'ctrl' => [
    ],
    'columns' => [
        'category' => [
            'label' => 'Foo',
            'config' => [
                'wizards' => [
                    '_PADDING' => 2,
                    '_VERTICAL' => 1,
                    'add' => [
                        'type' => 'script',
                        'title' => 'Create new record',
                        'icon' => 'actions-add',
                        'params' => [
                            'table' => 'tx_gs1serviceprovider_category',
                            'pid' => '###CURRENT_PID###',
                            'setValue' => 'prepend',
                        ],
                        'module' => [
                            'name' => 'wizard_add',
                        ],
                    ],
                    'list' => [
                        'type' => 'script',
                        'title' => 'List',
                        'icon' => 'actions-system-list-open',
                        'params' => [
                            'table' => 'tx_gs1serviceprovider_category',
                            'pid' => '###CURRENT_PID###',
                        ],
                        'module' => [
                            'name' => 'wizard_list',
                        ],
                    ],
                    'edit' => [
                        'type' => 'popup',
                        'title' => 'Edit',
                        'script' => 'actions-open',
                        'popup_onlyOpenIfSelected' => 1,
                        'icon' => 'edit2.gif',
                        'JSopenParams' => 'height=350,width=580,status=0,menubar=0,scrollbars=1',
                        'module' => [
                            'name' => 'wizard_edit',
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
        'category' => [
            'label' => 'Foo',
            'config' => [
                'fieldControl' => ['addRecord' => ['disabled' => false, 'options' => ['title' => 'Create new record', 'table' => 'tx_gs1serviceprovider_category', 'pid' => '###CURRENT_PID###', 'setValue' => 'prepend']], 'listModule' => ['disabled' => false, 'options' => ['title' => 'List', 'table' => 'tx_gs1serviceprovider_category', 'pid' => '###CURRENT_PID###']], 'editPopup' => ['disabled' => false, 'options' => ['title' => 'Edit']]],
            ],
        ],
    ],
];

?>

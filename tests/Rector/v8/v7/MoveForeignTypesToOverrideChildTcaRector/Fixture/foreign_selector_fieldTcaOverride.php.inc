<?php
return [
'ctrl' => [
],
    'columns' => [
        'aField' => [
            'config' => [
                'type' => 'inline',
                'foreign_selector' => 'uid_local',
                'foreign_selector_fieldTcaOverride' => [
                    'config' => [
                        'appearance' => [
                            'elementBrowserType' => 'file',
                        ],
                    ],
                ],
            ],
        ],
        'bField' => [
            'config' => [
                'type' => 'inline',
                'foreign_selector' => 'uid_remote',
                'foreign_selector_fieldTcaOverride' => [
                    'config' => [
                        'appearance' => [
                            'elementBrowserType' => 'file',
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
        'aField' => [
            'config' => [
                'type' => 'inline',
                'foreign_selector' => 'uid_local',
                'overrideChildTca' => ['columns' => ['uid_local' => [
                    'config' => [
                        'appearance' => [
                            'elementBrowserType' => 'file',
                        ],
                    ],
                ]]],
            ],
        ],
        'bField' => [
            'config' => [
                'type' => 'inline',
                'foreign_selector' => 'uid_remote',
                'overrideChildTca' => ['columns' => ['uid_remote' => [
                    'config' => [
                        'appearance' => [
                            'elementBrowserType' => 'file',
                        ],
                    ],
                ]]],
            ],
        ],
    ],
];
?>

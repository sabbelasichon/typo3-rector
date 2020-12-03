<?php

$rows = 42;
$format = 'html';

return [
    'ctrl' => [
    ],
    'columns' => [
        'bodytext' => [
            'config' => [
                'type' => 'text',
                'rows' => $rows,
                'wizards' => [
                    't3editor' => [
                        'type' => 'userFunc',
                        'userFunc' => 'TYPO3\CMS\T3editor\FormWizard->main',
                        'title' => 't3editor',
                        'icon' => 'wizard_table.gif',
                        'module' => [
                            'name' => 'wizard_table'
                        ],
                        'params' => [
                            'format' => $format,
                            'style' => 'width:98%; height: 60%;'
                        ],
                    ],
                ],
            ],
        ],
        'text' => [
            'config' => [
                'type' => 'text',
                'rows' => $rows,
                'wizards' => [
                    'some' => [
                    ],
                ],
            ],
        ],
    ],
];

?>
-----
<?php

$rows = 42;
$format = 'html';

return [
    'ctrl' => [
    ],
    'columns' => [
        'bodytext' => [
            'config' => [
                'type' => 'text',
                'rows' => $rows,
                'renderType' => 't3editor',
                'format' => $format,
            ],
        ],
        'text' => [
            'config' => [
                'type' => 'text',
                'rows' => $rows,
                'wizards' => [
                    'some' => [
                    ],
                ],
            ],
        ],
    ],
];

?>

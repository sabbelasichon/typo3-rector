<?php

return [
    'ctrl' => [
    ],
    'columns' => [
        'bodytext' => [
            'config' => [
                'type' => 'text',
                'wizards' => [
                    't3editorHtml' => [
                        'icon' => 'wizard_table.gif',
                    ],
                ],
            ],
        ],
        'foo' => [
            'config' => [
                'type' => 'text',
                'wizards' => [
                    't3editorHtml' => [
                        'icon' => 'edit2.gif',
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
        'bodytext' => [
            'config' => [
                'type' => 'text',
                'wizards' => [
                    't3editorHtml' => [
                        'icon' => 'content-table',
                    ],
                ],
            ],
        ],
        'foo' => [
            'config' => [
                'type' => 'text',
                'wizards' => [
                    't3editorHtml' => [
                        'icon' => 'actions-open',
                    ],
                ],
            ],
        ],
    ],
];

?>

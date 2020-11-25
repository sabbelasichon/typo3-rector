<?php

return [
    'ctrl' => [
    ],
    'columns' => [
        'content' => [
            'config' => [
                'type' => 'text',
            ],
            'defaultExtras' => 'richtext:rte_transform',
        ],
        'bodytext' => [
            'config' => [
                'type' => 'text',
            ],
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

return ['ctrl' => [], 'columns' => ['content' => ['config' => ['type' => 'text', 'enableRichtext' => true, 'richtextConfiguration' => 'default']], 'bodytext' => ['config' => ['type' => 'text']]], 'types' => ['myType' => ['columnsOverrides' => ['aField' => ['config' => ['enableRichtext' => true, 'richtextConfiguration' => 'default']]]]]];

?>

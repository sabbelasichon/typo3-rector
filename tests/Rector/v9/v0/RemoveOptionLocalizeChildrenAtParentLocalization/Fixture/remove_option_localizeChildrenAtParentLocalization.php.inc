<?php

return [
    'ctrl' => [],
    'columns' => [
        'foo' => [
            'config' =>
                [
                    'type' => 'inline',
                    'behaviour' => [
                        'localizeChildrenAtParentLocalization' => '1',
                    ],
                ],
        ],
    ],
];

?>
-----
<?php

return [
    'ctrl' => [],
    'columns' => [
        'foo' => [
            'config' =>
                [
                    'type' => 'inline',
                    'behaviour' => [],
                ],
        ],
    ],
];

?>

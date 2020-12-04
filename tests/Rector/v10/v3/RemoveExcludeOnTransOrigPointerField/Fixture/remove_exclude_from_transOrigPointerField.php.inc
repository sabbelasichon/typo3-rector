<?php

return [
    'ctrl' => [
        'transOrigPointerField' => 'l10n_parent',
    ],
    'columns' => [
        'l10n_parent' => [
            'exclude' => true,
            'config' => [
                'type' => 'select',
            ],
        ],
        'foo' => [
            'exclude' => true,
            'config' => [
                'type' => 'select',
            ],
        ],
    ],
];

?>
-----
<?php

return [
    'ctrl' => [
        'transOrigPointerField' => 'l10n_parent',
    ],
    'columns' => [
        'l10n_parent' => [
            'config' => [
                'type' => 'select',
            ],
        ],
        'foo' => [
            'exclude' => true,
            'config' => [
                'type' => 'select',
            ],
        ],
    ],
];

?>

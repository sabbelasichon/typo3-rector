<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\tca\MigrateItemsIndexedKeysToAssociativeRector\Fixture;

return [
    'ctrl' => [],
    'columns' => [
        'aColumn' => [
            'config' => [
                'type' => 'select',
                'renderType' => 'selectCheckBox',
                'items' => [
                    ['My label', 0, 'my-icon', 'group1', 'My Description'],
                    ['My label 1', 1, 'my-icon', 'group1', 'My Description'],
                    ['My label 2', 2, 'my-icon', 'group1', 'My Description'],
                ],
            ],
        ],
        'bColumn' => [
            'config' => [
                'type' => 'radio',
                'items' => [
                    ['My label', 0],
                    ['My label 1', 1],
                    ['My label 2', 2],
                ],
            ],
        ],
        'cColumn' => [
            'config' => [
                'type' => 'check',
                'items' => [
                    ['My label', 'invertStateDisplay' => 1],
                ],
            ],
        ],
    ],
];

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\tca\MigrateItemsIndexedKeysToAssociativeRector\Fixture;

return [
    'ctrl' => [],
    'columns' => [
        'aColumn' => [
            'config' => [
                'type' => 'select',
                'renderType' => 'selectCheckBox',
                'items' => [
                    ['label' => 'My label', 'value' => 0, 'icon' => 'my-icon', 'group' => 'group1', 'description' => 'My Description'],
                    ['label' => 'My label 1', 'value' => 1, 'icon' => 'my-icon', 'group' => 'group1', 'description' => 'My Description'],
                    ['label' => 'My label 2', 'value' => 2, 'icon' => 'my-icon', 'group' => 'group1', 'description' => 'My Description'],
                ]
            ],
        ],
        'bColumn' => [
            'config' => [
                'type' => 'radio',
                'items' => [
                    ['label' => 'My label', 'value' => 0],
                    ['label' => 'My label 1', 'value' => 1],
                    ['label' => 'My label 2', 'value' => 2],
                ],
            ],
        ],
        'cColumn' => [
            'config' => [
                'type' => 'check',
                'items' => [
                    ['label' => 'My label', 'invertStateDisplay' => 1],
                ],
            ],
        ],
    ],
];

?>

<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v3\tca\MigrateItemsIndexedKeysToAssociativeRector\Fixture;

return [
    'ctrl' => [],
    'columns' => [
        'aColumn' => [
            'config' => [
                'type' => 'select',
                'renderType' => 'selectCheckBox',
                'items' => [
                    ['value' => 'top', 'label' => 'top', 'icon' => 'top'],
                    ['value' => 'left', 'label' => 'left', 'icon' => 'left'],
                    ['value' => 'right', 'label' => 'right', 'icon' => 'right'],
                ],
            ],
        ],
        'bColumn' => [
            'config' => [
                'type' => 'select',
                'renderType' => 'selectCheckBox',
                'items' => [
                    ['description' => 'My Description', 'icon' => 'my-icon', 'group' => 'group1', 'label' => 'My label', 'value' => 0],
                    ['description' => 'My Description', 'icon' => 'my-icon', 'group' => 'group1', 'label' => 'My label 1', 'value' => 1],
                    ['description' => 'My Description', 'icon' => 'my-icon', 'group' => 'group1', 'label' => 'My label 2', 'value' => 2],
                    ['description' => 'My Description', 'icon' => 'my-icon', 'group' => 'group1', 'label' => 'My label 3', 'value' => 3],
                ],
            ],
        ],
    ],
];

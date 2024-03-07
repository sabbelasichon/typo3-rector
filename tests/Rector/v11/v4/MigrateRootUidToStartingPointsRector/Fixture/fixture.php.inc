<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v4\MigrateRootUidToStartingPointsRector\Fixture;

return [
    'ctrl' => [],
    'columns' => [
        'aField' => [
            'config' => [
                'type' => 'select',
                'renderType' => 'selectTree',
                'treeConfig' => [
                    'rootUid' => 42
                ],
            ],
        ],
        'bField' => [
            'config' => [
                'type' => 'category',
                'treeConfig' => [
                    'rootUid' => 43
                ],
            ],
        ],
        'cField' => [
            'config' => [
                'type' => 'select',
                'renderType' => 'selectTree',
            ],
        ],
        'dField' => [
            'config' => [
                'type' => 'input',
                'treeConfig' => [
                    'rootUid' => 43
                ],
            ],
        ],
        'alreadyMigratedField' => [
            'config' => [
                'type' => 'select',
                'renderType' => 'selectTree',
                'treeConfig' => [
                    'startingPoints' => '42'
                ],
            ],
        ],
    ],
];
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v4\MigrateRootUidToStartingPointsRector\Fixture;

return [
    'ctrl' => [],
    'columns' => [
        'aField' => [
            'config' => [
                'type' => 'select',
                'renderType' => 'selectTree',
                'treeConfig' => [
                    'startingPoints' => '42'
                ],
            ],
        ],
        'bField' => [
            'config' => [
                'type' => 'category',
                'treeConfig' => [
                    'startingPoints' => '43'
                ],
            ],
        ],
        'cField' => [
            'config' => [
                'type' => 'select',
                'renderType' => 'selectTree',
            ],
        ],
        'dField' => [
            'config' => [
                'type' => 'input',
                'treeConfig' => [
                    'rootUid' => 43
                ],
            ],
        ],
        'alreadyMigratedField' => [
            'config' => [
                'type' => 'select',
                'renderType' => 'selectTree',
                'treeConfig' => [
                    'startingPoints' => '42'
                ],
            ],
        ],
    ],
];
?>

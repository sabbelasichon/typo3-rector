<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateSingleDataStructureConfigurationRector\Fixture;

return [
    'ctrl' => [],
    'columns' => [
        'aColumn' => [
            'config' => [
                'type' => 'text',
            ],
        ],
        'bColumn' => [
            'config' => [
                'type' => 'flex',
            ],
        ],
        'cColumn' => [
            'config' => [
                'type' => 'flex',
                'ds' => '<some>flex</some>',
            ],
        ],
        'dColumn' => [
            'config' => [
                'type' => 'flex',
                'ds' => [
                    'default' => '<some>flex</some>',
                ],
            ],
        ],
        'eColumn' => [
            'config' => [
                'type' => 'flex',
                'ds' => [
                    '1,2' => '<some>flex</some>',
                    '2,1' => '<some>flex</some>',
                ],
            ],
        ],
    ],
];
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateSingleDataStructureConfigurationRector\Fixture;

return [
    'ctrl' => [],
    'columns' => [
        'aColumn' => [
            'config' => [
                'type' => 'text',
            ],
        ],
        'bColumn' => [
            'config' => [
                'type' => 'flex',
            ],
        ],
        'cColumn' => [
            'config' => [
                'type' => 'flex',
                'ds' => '<some>flex</some>',
            ],
        ],
        'dColumn' => [
            'config' => [
                'type' => 'flex',
                'ds' => '<some>flex</some>',
            ],
        ],
        'eColumn' => [
            'config' => [
                'type' => 'flex',
                'ds' => [
                    '1,2' => '<some>flex</some>',
                    '2,1' => '<some>flex</some>',
                ],
            ],
        ],
    ],
];
?>

<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v4\RenameTableOptionsAndCollateConnectionConfigurationRector\Fixture;

return [
    'BE' => [
        'debug' => true,
    ],
    'DB' => [
        'Connections' => [
            'Default' => [
                'charset' => 'utf8mb4',
                'host' => '127.0.0.1',
                'tableoptions' => [
                    'charset' => 'utf8mb4',
                    'collate' => 'utf8mb4_unicode_ci',
                ],
            ],
            'Custom' => [],
        ],
    ],
    'FE' => [
        'debug' => true,
    ],
];
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v4\RenameTableOptionsAndCollateConnectionConfigurationRector\Fixture;

return [
    'BE' => [
        'debug' => true,
    ],
    'DB' => [
        'Connections' => [
            'Default' => [
                'charset' => 'utf8mb4',
                'host' => '127.0.0.1',
                'defaultTableOptions' => [
                    'charset' => 'utf8mb4',
                    'collation' => 'utf8mb4_unicode_ci',
                ],
            ],
            'Custom' => [],
        ],
    ],
    'FE' => [
        'debug' => true,
    ],
];
?>

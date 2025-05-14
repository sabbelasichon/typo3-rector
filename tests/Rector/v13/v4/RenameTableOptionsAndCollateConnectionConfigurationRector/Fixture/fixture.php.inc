<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v4\RenameTableOptionsAndCollateConnectionConfigurationRector\Fixture;

return [
    'DB' => [
        'Connections' => [
            'Default' => [
                'tableoptions' => [
                    'collate' => 'utf8mb4_unicode_ci',
                ],
            ],
        ],
    ],
];
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v4\RenameTableOptionsAndCollateConnectionConfigurationRector\Fixture;

return [
    'DB' => [
        'Connections' => [
            'Default' => [
                'defaultTableOptions' => [
                    'collation' => 'utf8mb4_unicode_ci',
                ],
            ],
        ],
    ],
];
?>

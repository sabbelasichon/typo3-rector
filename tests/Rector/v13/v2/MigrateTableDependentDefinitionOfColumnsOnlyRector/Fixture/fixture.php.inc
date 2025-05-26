<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v2\MigrateTableDependentDefinitionOfColumnsOnlyRector\Fixture;

$urlParameters = [
    'edit' => [
        'pages' => [
            'foo' => 'edit',
        ],
    ],
    'columnsOnly' => 'title, nav_title, l18n_cfg, hidden',
];
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v2\MigrateTableDependentDefinitionOfColumnsOnlyRector\Fixture;

$urlParameters = [
    'edit' => [
        'pages' => [
            'foo' => 'edit',
        ],
    ],
    'columnsOnly' => ['pages' => ['title', 'nav_title', 'l18n_cfg', 'hidden']],
];
?>

<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v2\MigrateTableDependentDefinitionOfColumnsOnlyRector\Fixture;

$table = 'pages';
$row = [
    'record_uid' => 1,
    'field' => 'foo',
];

return [
    'editUrl' => (string)$this->uriBuilder->buildUriFromRoute('record_edit', [
        'edit' => [
            $table => [
                $row['record_uid'] => 'edit',
            ],
        ],
        'columnsOnly' => $row['field'],
    ]),
];
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v2\MigrateTableDependentDefinitionOfColumnsOnlyRector\Fixture;

$table = 'pages';
$row = [
    'record_uid' => 1,
    'field' => 'foo',
];

return [
    'editUrl' => (string)$this->uriBuilder->buildUriFromRoute('record_edit', [
        'edit' => [
            $table => [
                $row['record_uid'] => 'edit',
            ],
        ],
        'columnsOnly' => [$table => [$row['field']]],
    ]),
];
?>

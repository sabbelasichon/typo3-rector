<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\RemoveTcaControlOptionSearchFieldsRector\Fixture;

return [
    'ctrl' => [
        'title' => 'foobar',
        'searchFields' => 'title,description',
    ],
    'columns' => [
        'title' => [
            'config' => ['type' => 'text'],
        ],
        'notes' => [
            'config' => ['type' => 'text'],
        ],
        'brand' => [
            'config' => ['type' => 'color'],
        ],
        'file' => [
            'config' => ['type' => 'file'],
        ],
        'date' => [
            'config' => ['type' => 'datetime', 'dbType' => 'date'],
        ],
        'date2' => [
            'config' => ['type' => 'datetime'],
        ],
        'date3' => [
            'config' => ['type' => 'datetime', 'searchable' => false],
        ],
        'date4' => [
            'config' => ['type' => 'datetime', 'searchable' => true],
        ],
    ],
];
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\RemoveTcaControlOptionSearchFieldsRector\Fixture;

return [
    'ctrl' => [
        'title' => 'foobar',
    ],
    'columns' => [
        'title' => [
            'config' => ['type' => 'text'],
        ],
        'notes' => [
            'config' => ['type' => 'text', 'searchable' => false],
        ],
        'brand' => [
            'config' => ['type' => 'color', 'searchable' => false],
        ],
        'file' => [
            'config' => ['type' => 'file'],
        ],
        'date' => [
            'config' => ['type' => 'datetime', 'dbType' => 'date'],
        ],
        'date2' => [
            'config' => ['type' => 'datetime', 'searchable' => false],
        ],
        'date3' => [
            'config' => ['type' => 'datetime', 'searchable' => false],
        ],
        'date4' => [
            'config' => ['type' => 'datetime', 'searchable' => true],
        ],
    ],
];
?>

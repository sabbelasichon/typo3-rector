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

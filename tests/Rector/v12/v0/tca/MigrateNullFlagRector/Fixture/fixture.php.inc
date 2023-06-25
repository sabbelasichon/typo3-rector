<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\tca\MigrateNullFlagRector\Fixture;

$slugBehaviour = $GLOBALS['SLUG'];

return [
    'ctrl' => [],
    'columns' => [
        'nullable_column1' => [
            'config' => [
                'eval' => 'null,trim',
            ],
        ],
        'nullable_column2' => [
            'config' => [
                'eval' => 'null',
            ],
        ],
        'already_nullable_column' => [
            'config' => [
                'eval' => 'null',
                'nullable' => true,
            ],
        ],
        'employment_type' => [
            'config' => [
                'eval' => ''
            ],
        ],
        'slug' => [
            'config' => [
                'eval' => $slugBehaviour,
            ],
        ],
    ],
];

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\tca\MigrateNullFlagRector\Fixture;

$slugBehaviour = $GLOBALS['SLUG'];

return [
    'ctrl' => [],
    'columns' => [
        'nullable_column1' => [
            'config' => [
                'eval' => 'trim', 'nullable' => true,
            ],
        ],
        'nullable_column2' => [
            'config' => [
                'nullable' => true,
            ],
        ],
        'already_nullable_column' => [
            'config' => ['nullable' => true],
        ],
        'employment_type' => [
            'config' => [
                'eval' => ''
            ],
        ],
        'slug' => [
            'config' => [
                'eval' => $slugBehaviour,
            ],
        ],
    ],
];

?>

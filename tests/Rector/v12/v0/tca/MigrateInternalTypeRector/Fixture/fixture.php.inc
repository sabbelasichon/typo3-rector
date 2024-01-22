<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\tca\MigrateInternalTypeRector\Fixture;

return [
    'ctrl' => [],
    'columns' => [
        'aColumn' => [
            'config' => [
                'type' => 'group',
                'internal_type' => 'folder',
                'maxitems' => 2,
            ],
        ],
        'bColumn' => [
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'minitems' => 2,
            ],
        ],
    ],
];

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\tca\MigrateInternalTypeRector\Fixture;

return [
    'ctrl' => [],
    'columns' => [
        'aColumn' => [
            'config' => [
                'type' => 'folder',
                'maxitems' => 2,
            ],
        ],
        'bColumn' => [
            'config' => [
                'type' => 'group',
                'minitems' => 2,
            ],
        ],
    ],
];

?>

<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\tca\MigrateColsToSizeForTcaTypeNoneRector\Fixture;

return [
    'ctrl' => [],
    'columns' => [
        'aColumn' => [
            'config' => [
                'type' => 'none',
                'cols' => 20,
            ],
        ],
        'bColumn' => [
            'config' => [
                'type' => 'none',
                'cols' => 20,
                'size' => 50,
            ],
        ],
        'cColumn' => [
            'config' => [
                'type' => 'text',
                'cols' => '40',
            ],
        ],
    ],
];

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\tca\MigrateColsToSizeForTcaTypeNoneRector\Fixture;

return [
    'ctrl' => [],
    'columns' => [
        'aColumn' => [
            'config' => [
                'type' => 'none',
                'size' => 20,
            ],
        ],
        'bColumn' => [
            'config' => [
                'type' => 'none',
                'size' => 20,
            ],
        ],
        'cColumn' => [
            'config' => [
                'type' => 'text',
                'cols' => '40',
            ],
        ],
    ],
];

?>

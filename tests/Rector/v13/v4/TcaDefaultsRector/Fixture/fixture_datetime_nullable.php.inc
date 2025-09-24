<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v4\TcaDefaultsRector\Fixture;

return [
    'ctrl' => [],
    'columns' => [
        'aColumn' => [
            'config' => [
                'type' => 'datetime',
                'nullable' => true,
            ],
        ],
    ],
];
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v4\TcaDefaultsRector\Fixture;

return [
    'ctrl' => [],
    'columns' => [
        'aColumn' => [
            'config' => [
                'type' => 'datetime',
                'nullable' => true,
                'default' => null,
            ],
        ],
    ],
];
?>

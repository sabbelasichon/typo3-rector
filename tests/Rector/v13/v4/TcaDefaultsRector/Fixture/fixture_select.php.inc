<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v4\TcaDefaultsRector\Fixture;

return [
    'ctrl' => [],
    'columns' => [
        'aColumn' => [
            'config' => [
                'type' => 'select',
            ]
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
                'type' => 'select', 'default' => 0,
            ]
        ],
    ],
];
?>

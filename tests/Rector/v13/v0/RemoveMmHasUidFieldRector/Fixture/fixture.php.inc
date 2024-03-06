<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\RemoveMmHasUidFieldRector\Fixture;

return [
    'ctrl' => [],
    'columns' => [
        'aColumn' => [
            'config' => [
                'type' => 'group',
                'MM_hasUidField' => false,
            ],
        ],
    ],
];
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\RemoveMmHasUidFieldRector\Fixture;

return [
    'ctrl' => [],
    'columns' => [
        'aColumn' => [
            'config' => [
                'type' => 'group',
            ],
        ],
    ],
];
?>

<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v5\RemoveDefaultInternalTypeDBRector\Fixture;

return [
    'ctrl' => [
    ],
    'columns' => [
        'foobar' => [
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
            ],
        ],
    ],
];

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v5\RemoveDefaultInternalTypeDBRector\Fixture;

return [
    'ctrl' => [
    ],
    'columns' => [
        'foobar' => [
            'config' => [
                'type' => 'group',
            ],
        ],
    ],
];

?>

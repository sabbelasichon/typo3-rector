<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v8\v6\AddTypeToColumnConfigRector\Fixture;

return [
    'ctrl' => [],
    'columns' => [
        'foo' => [
            'config' => [
            ],
        ],
        'bar' => [
        ],
        'baz' => [
            'config' => [
                'type' => 'select'
            ]
        ]
    ]
];

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v8\v6\AddTypeToColumnConfigRector\Fixture;

return [
    'ctrl' => [],
    'columns' => [
        'foo' => [
            'config' => [['type' => 'none']],
        ],
        'bar' => ['config' => ['type' => 'none']],
        'baz' => [
            'config' => [
                'type' => 'select'
            ]
        ]
    ]
];

?>

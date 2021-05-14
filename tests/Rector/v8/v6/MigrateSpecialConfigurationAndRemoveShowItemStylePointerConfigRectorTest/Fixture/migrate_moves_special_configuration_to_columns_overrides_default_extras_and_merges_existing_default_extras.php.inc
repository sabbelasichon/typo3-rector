<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v8\v6\AddTypeToColumnConfigRector\Fixture;

return [
    'ctrl' => [],
    'columns' => [
        'anotherField' => [
            'defaultExtras' => 'nowrap',
        ],
    ],
    'types' => [
        0 => [
            'showitem' => 'aField,anotherField;with;;enable-tab,thirdField',
        ],
    ],
];

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v8\v6\AddTypeToColumnConfigRector\Fixture;

return [
    'ctrl' => [],
    'columns' => [
        'anotherField' => [
            'defaultExtras' => 'nowrap',
        ],
    ],
    'types' => [
        0 => [
            'showitem' => 'aField,anotherField;with,thirdField', 'columnsOverrides' => ['anotherField' => ['defaultExtras' => 'nowrap:enable-tab']],
        ],
    ],
];

?>

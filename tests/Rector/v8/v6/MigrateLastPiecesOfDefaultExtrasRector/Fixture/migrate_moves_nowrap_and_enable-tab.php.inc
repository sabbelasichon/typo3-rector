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
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v8\v6\AddTypeToColumnConfigRector\Fixture;

return [
    'ctrl' => [],
    'columns' => [
        'anotherField' => [
            'config' => ['wrap' => 'off'],
        ],
    ],
    'types' => [
        0 => [
            'showitem' => 'aField,anotherField;with,thirdField', 'columnsOverrides' => ['anotherField' => ['config' => ['wrap' => 'off', 'enableTabulator' => true]]],
        ],
    ],
];

?>

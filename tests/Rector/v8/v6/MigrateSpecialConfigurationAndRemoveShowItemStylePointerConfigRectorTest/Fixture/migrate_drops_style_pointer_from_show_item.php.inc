<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v8\v6\AddTypeToColumnConfigRector\Fixture;

return [
    'ctrl' => [],
    'columns' => [],
    'types' => [
        0 => [
            'showitem' => 'aField,anotherField;with;;;style-pointer,thirdField',
        ],
        1 => [
            'showitem' => 'aField,;;;;only-a-style-pointer,anotherField',
        ],
    ],
];

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v8\v6\AddTypeToColumnConfigRector\Fixture;

return [
    'ctrl' => [],
    'columns' => [],
    'types' => [
        0 => [
            'showitem' => 'aField,anotherField;with,thirdField',
        ],
        1 => [
            'showitem' => 'aField,anotherField',
        ],
    ],
];

?>

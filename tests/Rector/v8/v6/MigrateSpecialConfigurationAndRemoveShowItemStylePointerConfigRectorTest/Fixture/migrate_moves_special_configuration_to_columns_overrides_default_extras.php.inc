<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v8\v6\AddTypeToColumnConfigRector\Fixture;

return [
    'ctrl' => [],
    'columns' => [],
    'types' => [
        0 => [
            'showitem' => 'aField,anotherField;with;;nowrap,thirdField',
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
            'showitem' => 'aField,anotherField;with,thirdField', 'columnsOverrides' => ['anotherField' => ['defaultExtras' => 'nowrap']],
        ],
    ],
];

?>

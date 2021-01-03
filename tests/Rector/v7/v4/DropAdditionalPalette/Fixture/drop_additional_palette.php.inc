<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v7\v4\DropAdditionalPalette\Fixture;

return [
    'ctrl' => [
    ],
    'types' => [
        'aType' => [
            'showitem' => 'aField;aLabel;anAdditionalPaletteName, aField;aLabel, --palette--;;anAdditionalPaletteName',
        ],
    ],
    'columns' => [
    ],
];

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v7\v4\DropAdditionalPalette\Fixture;

return [
    'ctrl' => [
    ],
    'types' => [
        'aType' => [
            'showitem' => 'aField;aLabel,--palette--;;anAdditionalPaletteName,aField;aLabel,--palette--;;anAdditionalPaletteName',
        ],
    ],
    'columns' => [
    ],
];

?>

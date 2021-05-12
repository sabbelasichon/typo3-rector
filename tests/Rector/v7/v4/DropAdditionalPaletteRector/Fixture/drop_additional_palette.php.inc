<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v7\v4\DropAdditionalPaletteRector\Fixture;

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

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v7\v4\DropAdditionalPaletteRector\Fixture;

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

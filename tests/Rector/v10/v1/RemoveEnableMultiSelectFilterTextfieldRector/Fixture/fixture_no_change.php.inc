<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v10\v1\RemoveEnableMultiSelectFilterTextfieldRector\Fixture;

return [
    'ctrl' => [
    ],
    'columns' => [
        'foo' => [
            'label' => 'foo',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'enableMultiSelectFilterTextfield' => false,
            ],
        ],
    ],
];

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v10\v1\RemoveEnableMultiSelectFilterTextfieldRector\Fixture;

return [
    'ctrl' => [
    ],
    'columns' => [
        'foo' => [
            'label' => 'foo',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'enableMultiSelectFilterTextfield' => false,
            ],
        ],
    ],
];

?>

<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v5\SimplifyCheckboxItemsTCARector\Fixture;

return [
    'ctrl' => [],
    'columns' => [
        'col_name' => [
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        'LLL:EXT:some_extension/Resources/Private/Language/locallang_db.xlf:some_label',
                        '',
                    ],
                ],
            ],
        ],
    ],
];
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v5\SimplifyCheckboxItemsTCARector\Fixture;

return [
    'ctrl' => [],
    'columns' => [
        'col_name' => [
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        0 => 'LLL:EXT:some_extension/Resources/Private/Language/locallang_db.xlf:some_label',
                    ],
                ],
            ],
        ],
    ],
];
?>

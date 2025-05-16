<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v5\SimplifyCheckboxItemsTCARector\Fixture;

return [
    'ctrl' => [],
    'columns' => [
        'hidden' => [
            'exclude' => true,
            'label' => 'Bearbeitet',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        'label' => '',
                        'value' => '',
                        'invertStateDisplay' => true,
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
        'hidden' => [
            'exclude' => true,
            'label' => 'Bearbeitet',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        'label' => '',
                        'invertStateDisplay' => true,
                    ],
                ],
            ],
        ],
    ],
];
?>

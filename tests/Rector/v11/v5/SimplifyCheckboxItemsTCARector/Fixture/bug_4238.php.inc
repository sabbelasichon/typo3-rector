<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v5\SimplifyCheckboxItemsTCARector\Fixture;

return [
    'ctrl' => [],
    'columns' => [
        'show_canonical' => [
            'exclude' => 1,
            'l10n_mode' => 'mergeIfNotBlank',
            'label' => 'show_canonical',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'invertStateDisplay' => '1',
                'items' => [
                    ['label' => ''],
                ],
            ],
        ],
        'no_archive' => [
            'exclude' => 1,
            'l10n_mode' => 'exclude',
            'label' => 'no_archive',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    ['label' => ''],
                ],
            ],
        ],
    ],
];

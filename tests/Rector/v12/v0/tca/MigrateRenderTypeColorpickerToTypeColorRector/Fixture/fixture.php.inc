<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\tca\MigrateRenderTypeColorpickerToTypeColorRector\Fixture;

return [
    'ctrl' => [],
    'columns' => [
        'colorPicker' => [
            'config' => [
                'type' => 'input',
                'renderType' => 'colorpicker',
                'required' => true,
                'size' => 20,
                'max' => 1234,
                'eval' => 'trim,null',
                'valuePicker' => [
                    'items' => [
                        ['typo3 orange', '#FF8700'],
                    ],
                ],
            ],
        ],
        'colorPicker2' => [
            'config' => [
                'type' => 'input',
                'renderType' => 'colorpicker',
                'eval' => 'trim',
            ],
        ],
    ],
];
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\tca\MigrateRenderTypeColorpickerToTypeColorRector\Fixture;

return [
    'ctrl' => [],
    'columns' => [
        'colorPicker' => [
            'config' => [
                'type' => 'color',
                'required' => true,
                'size' => 20,
                'eval' => 'null',
                'valuePicker' => [
                    'items' => [
                        ['typo3 orange', '#FF8700'],
                    ],
                ],
            ],
        ],
        'colorPicker2' => [
            'config' => [
                'type' => 'color',
            ],
        ],
    ],
];
?>

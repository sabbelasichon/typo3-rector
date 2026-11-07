<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v2\RemoveTcaDescriptionFieldInformationRector\Fixture;

return [
    'ctrl' => [],
    'columns' => [
        'my_field' => [
            'label' => 'My field',
            'description' => 'Help text for this field',
            'config' => [
                'type' => 'input',
                'fieldInformation' => [
                    'tcaDescription' => [
                        'renderType' => 'tcaDescription',
                    ],
                ],
            ],
        ],
        'other_key' => [
            'label' => 'Other key',
            'config' => [
                'type' => 'input',
                'fieldInformation' => [
                    'description' => [
                        'renderType' => 'tcaDescription',
                    ],
                    'myCustomInfo' => [
                        'renderType' => 'myCustomInfo',
                    ],
                ],
            ],
        ],
        'disabled' => [
            'label' => 'Disabled',
            'config' => [
                'type' => 'input',
                'fieldInformation' => [
                    'tcaDescription' => [
                        'disabled' => true,
                    ],
                ],
            ],
        ],
    ],
    'types' => [
        '0' => [
            'showitem' => 'my_field',
            'columnsOverrides' => [
                'my_field' => [
                    'config' => [
                        'fieldInformation' => [
                            'tcaDescription' => [
                                'renderType' => 'tcaDescription',
                            ],
                            'myCustomInfo' => [
                                'renderType' => 'myCustomInfo',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
];

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v2\RemoveTcaDescriptionFieldInformationRector\Fixture;

return [
    'ctrl' => [],
    'columns' => [
        'my_field' => [
            'label' => 'My field',
            'description' => 'Help text for this field',
            'config' => [
                'type' => 'input',
            ],
        ],
        'other_key' => [
            'label' => 'Other key',
            'config' => [
                'type' => 'input',
                'fieldInformation' => [
                    'myCustomInfo' => [
                        'renderType' => 'myCustomInfo',
                    ],
                ],
            ],
        ],
        'disabled' => [
            'label' => 'Disabled',
            'config' => [
                'type' => 'input',
            ],
        ],
    ],
    'types' => [
        '0' => [
            'showitem' => 'my_field',
            'columnsOverrides' => [
                'my_field' => [
                    'config' => [
                        'fieldInformation' => [
                            'myCustomInfo' => [
                                'renderType' => 'myCustomInfo',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
];

?>

<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v2\RemoveTcaDescriptionFieldInformationRector\Fixture;

return [
    'ctrl' => [],
    'columns' => [
        'my_field' => [
            'label' => 'My field',
            'config' => [
                'type' => 'input',
                'fieldInformation' => [
                    'myCustomInfo' => [
                        'renderType' => 'myCustomInfo',
                    ],
                    'tcaDescription' => [
                        'renderType' => 'myOwnDescription',
                    ],
                ],
            ],
        ],
    ],
];

?>

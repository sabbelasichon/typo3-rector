<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v2\RemoveTcaDescriptionFieldInformationRector\Fixture;

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

ExtensionManagementUtility::addTCAcolumns('tt_content', [
    'my_field' => [
        'label' => 'My field',
        'config' => [
            'type' => 'input',
            'fieldInformation' => [
                'tcaDescription' => [
                    'renderType' => 'tcaDescription',
                ],
            ],
        ],
    ],
]);

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v2\RemoveTcaDescriptionFieldInformationRector\Fixture;

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

ExtensionManagementUtility::addTCAcolumns('tt_content', [
    'my_field' => [
        'label' => 'My field',
        'config' => [
            'type' => 'input',
        ],
    ],
]);

?>

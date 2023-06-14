<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\tca\RemoveTableLocalPropertyRector\Fixture;

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

return [
    'ctrl' => [],
    'columns' => [
        'images' => [
            'exclude' => 1,
            'label' => 'Bilder',
            'config' => ExtensionManagementUtility::getFileFieldTCAConfig(
                'images',
                [
                    'appearance' => [
                        'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:images.addFileReference',
                        'showPossibleLocalizationRecords' => true,
                        'showRemovedLocalizationRecords' => true,
                        'showAllLocalizationLink' => true,
                        'showSynchronizationLink' => true,
                    ],
                    'foreign_match_fields' => [
                        'fieldname' => 'images',
                        'tablenames' => 'tx_aknwevents_domain_model_event',
                        'table_local' => 'sys_file',
                    ],
                    'minitems' => 0,
                    'maxitems' => 999,
                ],
                $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
            ),
        ],
        'images2' => [
            'exclude' => 1,
            'label' => 'Bilder',
            'config' => ExtensionManagementUtility::getFileFieldTCAConfig(
                'images',
                [
                    'appearance' => [
                        'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:images.addFileReference',
                        'showPossibleLocalizationRecords' => true,
                        'showRemovedLocalizationRecords' => true,
                        'showAllLocalizationLink' => true,
                        'showSynchronizationLink' => true,
                    ],
                    'foreign_match_fields' => [
                        'fieldname' => 'images',
                        'tablenames' => 'tx_aknwevents_domain_model_event',
                    ],
                    'minitems' => 0,
                    'maxitems' => 999,
                ],
                $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
            ),
        ],
    ],
];

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\tca\RemoveTableLocalPropertyRector\Fixture;

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

return [
    'ctrl' => [],
    'columns' => [
        'images' => [
            'exclude' => 1,
            'label' => 'Bilder',
            'config' => ExtensionManagementUtility::getFileFieldTCAConfig(
                'images',
                [
                    'appearance' => [
                        'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:images.addFileReference',
                        'showPossibleLocalizationRecords' => true,
                        'showRemovedLocalizationRecords' => true,
                        'showAllLocalizationLink' => true,
                        'showSynchronizationLink' => true,
                    ],
                    'foreign_match_fields' => [
                        'fieldname' => 'images',
                        'tablenames' => 'tx_aknwevents_domain_model_event',
                    ],
                    'minitems' => 0,
                    'maxitems' => 999,
                ],
                $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
            ),
        ],
        'images2' => [
            'exclude' => 1,
            'label' => 'Bilder',
            'config' => ExtensionManagementUtility::getFileFieldTCAConfig(
                'images',
                [
                    'appearance' => [
                        'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:images.addFileReference',
                        'showPossibleLocalizationRecords' => true,
                        'showRemovedLocalizationRecords' => true,
                        'showAllLocalizationLink' => true,
                        'showSynchronizationLink' => true,
                    ],
                    'foreign_match_fields' => [
                        'fieldname' => 'images',
                        'tablenames' => 'tx_aknwevents_domain_model_event',
                    ],
                    'minitems' => 0,
                    'maxitems' => 999,
                ],
                $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
            ),
        ],
    ],
];

?>

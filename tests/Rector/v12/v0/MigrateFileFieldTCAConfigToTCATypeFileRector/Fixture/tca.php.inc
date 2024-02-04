<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\MigrateFileFieldTCAConfigToTCATypeFileRector\Fixture;

use TYPO3\CMS\Core\Resource\AbstractFile;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

return [
    'columns' => [
        'logo' => [
            'exclude' => false,
            'label' => 'Label',
            'config' => ExtensionManagementUtility::getFileFieldTCAConfig(
                'logo',
                [
                    'maxitems' => 1,
                    'appearance' => [
                        'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:images.addFileReference',
                        'fileUploadAllowed' => 0
                    ],
                    'overrideChildTca' => [
                        'types' => [
                            '0' => [
                                'showitem' => '
                            --palette--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
                            ],
                            AbstractFile::FILETYPE_IMAGE => [
                                'showitem' => '
                            --palette--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
                            ],
                        ],
                    ],
                ],
                $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
            ),
        ],
    ]
];

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\MigrateFileFieldTCAConfigToTCATypeFileRector\Fixture;

use TYPO3\CMS\Core\Resource\AbstractFile;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

return [
    'columns' => [
        'logo' => [
            'exclude' => false,
            'label' => 'Label',
            'config' => [
                ### !!! Watch out for fieldName different from columnName
                'type' => 'file',
                'allowed' => $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'],
                'maxitems' => 1,
                'appearance' => [
                    'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:images.addFileReference',
                    'fileUploadAllowed' => 0
                ],
                'overrideChildTca' => [
                    'types' => [
                        '0' => [
                            'showitem' => '
                            --palette--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
                        ],
                        AbstractFile::FILETYPE_IMAGE => [
                            'showitem' => '
                            --palette--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
                        ],
                    ],
                ],
            ],
        ],
    ]
];

?>

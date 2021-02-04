<?php
$xlf = 'LLL:EXT:...';
return [
    'ctrl' => [
        'title' => $xlf . ':tx_blog_post',
        'label' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'delete' => 'deleted',
        'enablecolumns' => [
        ],
        'searchFields' => 'title,files',
    ],
    'interface' => [
        'showRecordFieldList' => 'title,files',
    ],
    'types' => [
        '1' => ['showitem' => 'title,files'],
    ],
    'columns' => [
        'title' => [
            'exclude' => true,
            'label' => $xlf . 'title',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required',
            ],
        ],
        'files' => [
            'exclude' => true,
            'label' => $xlf . 'files',
            'config' => TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'files',
                [
                    'appearance' => [
                        'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:media.addFileReference',
                    ],
                    'foreign_types' => [
                        '0' => [
                            'showitem' => '
                    --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                    --palette--;;filePalette',
                        ],
                        1 => [
                            'showitem' => '
                    --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                    --palette--;;filePalette',
                        ],
                    ],
                    'maxitems' => 100,
                ]
            ),
        ],
    ],
];
?>
-----
<?php
$xlf = 'LLL:EXT:...';
return [
    'ctrl' => [
        'title' => $xlf . ':tx_blog_post',
        'label' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'delete' => 'deleted',
        'enablecolumns' => [
        ],
        'searchFields' => 'title,files',
    ],
    'interface' => [
        'showRecordFieldList' => 'title,files',
    ],
    'types' => [
        '1' => ['showitem' => 'title,files'],
    ],
    'columns' => [
        'title' => [
            'exclude' => true,
            'label' => $xlf . 'title',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required',
            ],
        ],
        'files' => [
            'exclude' => true,
            'label' => $xlf . 'files',
            'config' => TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'files',
                [
                    'appearance' => [
                        'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:media.addFileReference',
                    ],
                    'maxitems' => 100,
                    'overrideChildTca' => ['types' => [
                        '0' => [
                            'showitem' => '
                    --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                    --palette--;;filePalette',
                        ],
                        1 => [
                            'showitem' => '
                    --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                    --palette--;;filePalette',
                        ],
                    ]],
                ]
            ),
        ],
    ],
];
?>

<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v4\MigrateFileFolderConfigurationRector\Fixture;

return [
    'ctrl' => [
    ],
    'columns' => [
        'aField' => [
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'fileFolder' => 'EXT:my_ext/Resources/Public/Icons',
                'fileFolder_extList' => 'svg',
                'fileFolder_recursions' => 1,
            ]
        ],
        "iconSelector" => [
            "label" => "Icon auswählen",
            "description" => "",
            "config" => [
                "type" => "select",
                "items" => [["", ""]],
                "renderType" => "selectSingle",
                "fileFolder" => "EXT:site_package/Modules/GlobalTemplates/Assets/icons/selectable_icons_be/",
                "fileFolder_extList" => "svg",
                "fileFolder_recursions" => 0,
                "fieldWizard" => [
                    "selectIcons" => [
                        "disabled" => false,
                    ],
                ],
            ],
        ],
    ],
];

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v4\MigrateFileFolderConfigurationRector\Fixture;

return [
    'ctrl' => [
    ],
    'columns' => [
        'aField' => [
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'fileFolderConfig' => ['folder' => 'EXT:my_ext/Resources/Public/Icons', 'allowedExtensions' => 'svg', 'depth' => 1],
            ]
        ],
        "iconSelector" => [
            "label" => "Icon auswählen",
            "description" => "",
            "config" => [
                "type" => "select",
                "items" => [["", ""]],
                "renderType" => "selectSingle",
                "fieldWizard" => [
                    "selectIcons" => [
                        "disabled" => false,
                    ],
                ],
                'fileFolderConfig' => ['folder' => "EXT:site_package/Modules/GlobalTemplates/Assets/icons/selectable_icons_be/", 'allowedExtensions' => "svg", 'depth' => 0],
            ],
        ],
    ],
];

?>

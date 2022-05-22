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
        ]
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
        ]
    ],
];

?>

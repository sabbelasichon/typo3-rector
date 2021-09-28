<?php

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

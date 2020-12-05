<?php

return [
    'ctrl' => [
    ],
    'columns' => [
        'file_mountpoints' => [
            'label' => 'LLL:EXT:lang/locallang_tca.xlf:file_mountpoints',
            'config' => [
                'type' => 'select',
                'foreign_table' => 'sys_filemounts',
                'foreign_table_where' => ' AND sys_filemounts.pid=0 ORDER BY sys_filemounts.title',
                'size' => '3',
                'maxitems' => 25,
                'autoSizeMax' => 10,
                'iconsInOptionTags' => 1,
            ],
        ],
    ],
];

?>
-----
<?php

return [
    'ctrl' => [
    ],
    'columns' => [
        'file_mountpoints' => [
            'label' => 'LLL:EXT:lang/locallang_tca.xlf:file_mountpoints',
            'config' => [
                'type' => 'select',
                'foreign_table' => 'sys_filemounts',
                'foreign_table_where' => ' AND sys_filemounts.pid=0 ORDER BY sys_filemounts.title',
                'size' => '3',
                'maxitems' => 25,
                'autoSizeMax' => 10,
            ],
        ],
    ],
];

?>

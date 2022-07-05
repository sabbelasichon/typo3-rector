<?php

$_LLL_general = 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf';

return [
    'ctrl' => [
        'languageField' => 'sys_language_uid',
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => 1,
            'label' => $_LLL_general . ':LGL.language',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'sys_language',
                'foreign_table_where' => 'ORDER BY sys_language.title',
                'eval' => 'int',
                'items' => [
                    [$_LLL_general . ':LGL.allLanguages', -1],
                    [$_LLL_general . ':LGL.default_value', 0]
                ],
            ],
        ],
    ],
];

?>
-----
<?php

$_LLL_general = 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf';

return [
    'ctrl' => [
        'languageField' => 'sys_language_uid',
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => 1,
            'label' => $_LLL_general . ':LGL.language',
            'config' => ['type' => 'language'],
        ],
    ],
];

?>

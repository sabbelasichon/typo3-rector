<?php

return [
    'ctrl' => [
        'languageField' => 'sys_language_uid',
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'input',
            ],
        ],
    ],
];

?>

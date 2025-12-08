<?php

$GLOBALS['TCA']['sys_file_collection']['types']['mytype'] = [
    'showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource, title, --palette--;;1, type, description, my_field',
];

$GLOBALS['TCA']['sys_file_collection']['columns']['type']['config']['items'][] = [
    'label' => 'My Collection Type',
    'value' => 'mytype',
];

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
    'sys_file_collection',
    [
        'my_field' => [
            'config' => [
                'type' => 'input',
            ],
        ],
    ],
);

<?php

// file exists

$GLOBALS['TCA']['sys_file_collection']['types']['myothertype'] = [
    'showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource, title, --palette--;;1, type, other_description, my_other_field',
];

$GLOBALS['TCA']['sys_file_collection']['columns']['type']['config']['items'][] = [
    'label' => 'My Other Collection Type',
    'value' => 'myothertype',
];

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
    'sys_file_collection',
    [
        'my_other_field' => [
            'config' => [
                'type' => 'input',
            ],
        ],
    ],
);

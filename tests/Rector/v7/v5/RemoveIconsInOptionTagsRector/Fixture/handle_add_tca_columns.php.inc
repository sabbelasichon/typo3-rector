<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

$tmp_columns = [
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
];
ExtensionManagementUtility::addTCAcolumns('tx_offers_domain_model_offer',
    $tmp_columns);

?>
-----
<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

$tmp_columns = [
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
];
ExtensionManagementUtility::addTCAcolumns('tx_offers_domain_model_offer',
    $tmp_columns);

?>

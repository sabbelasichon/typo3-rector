<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

$tmp_showings_columns = [
    'showing' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:offers/Resources/Private/Language/locallang_db.xlf:tx_showings_domain_model_spaceunit.showing',
        'config' => [
            'type' => 'select',
            'foreign_table' => 'tx_showings_domain_model_showing',
            'items' => [
                ['', '']
            ],
            'readOnly' => 1,
        ],
    ],
];
ExtensionManagementUtility::addTCAcolumns('tx_offers_domain_model_offer',
    $tmp_showings_columns);

?>
-----
<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

$tmp_showings_columns = [
    'showing' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:offers/Resources/Private/Language/locallang_db.xlf:tx_showings_domain_model_spaceunit.showing',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectSingle',
            'foreign_table' => 'tx_showings_domain_model_showing',
            'items' => [
                ['', '']
            ],
            'readOnly' => 1,
        ],
    ],
];
ExtensionManagementUtility::addTCAcolumns('tx_offers_domain_model_offer',
    $tmp_showings_columns);

?>

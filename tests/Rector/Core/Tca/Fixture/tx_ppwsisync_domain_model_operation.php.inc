<?php

return [
    'ctrl' => [
        'title' => 'LLL:EXT:ppw_si_sync/Resources/Private/Language/locallang_db.xlf:tx_ppwsisync_domain_model_operation',
        'label' => 'entity_name',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'hideTable' => 1,
        'versioningWS' => 0,
        'sortby' => 'sorting',
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'iconfile' => 'EXT:ppw_si_sync/Resources/Public/Icons/tx_ppwsisync_domain_model_operation.gif',
    ],
    'interface' => [
        'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, client_name, entity_name, xml_data',
    ],
    'types' => [
        1 => [
            'showitem' => 'client_name,entity_name,xml_data,--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access,sys_language_uid,l10n_parent,l10n_diffsource,hidden,starttime,endtime',
        ],
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'select',
                'foreign_table' => 'sys_language',
                'foreign_table_where' => 'ORDER BY sys_language.title',
                'items' => [
                    [
                        'LLL:EXT:lang/locallang_general.xlf:LGL.allLanguages',
                        -1,
                    ],
                    [
                        'LLL:EXT:lang/locallang_general.xlf:LGL.default_value',
                        0,
                    ],
                ],
            ],
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    [
                        '',
                        0,
                    ],
                ],
                'foreign_table' => 'tx_ppwsisync_domain_model_operation',
                'foreign_table_where' => 'AND tx_ppwsisync_domain_model_operation.pid=###CURRENT_PID### AND tx_ppwsisync_domain_model_operation.sys_language_uid IN (-1,0)',
            ],
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'hidden' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
            'config' => [
                'type' => 'check',
            ],
        ],
        'starttime' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'input',
                'size' => 13,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0,
                'range' => [
                    'lower' => 1536732000,
                ],
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],
        'endtime' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'input',
                'size' => 13,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0,
                'range' => [
                    'lower' => 1536732000,
                ],
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],
        'client_name' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:ppw_si_sync/Resources/Private/Language/locallang_db.xlf:tx_ppwsisync_domain_model_operation.client_name',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'entity_name' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:ppw_si_sync/Resources/Private/Language/locallang_db.xlf:tx_ppwsisync_domain_model_operation.entity_name',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ),
        'xml_data' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:ppw_si_sync/Resources/Private/Language/locallang_db.xlf:tx_ppwsisync_domain_model_operation.xml_data',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
            ],
        ],
        'parent_transaction' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
    ],
];
?>
-----
<?php

return ['ctrl' => ['title' => 'LLL:EXT:ppw_si_sync/Resources/Private/Language/locallang_db.xlf:tx_ppwsisync_domain_model_operation', 'label' => 'entity_name', 'tstamp' => 'tstamp', 'crdate' => 'crdate', 'cruser_id' => 'cruser_id', 'hideTable' => 1, 'versioningWS' => false, 'sortby' => 'sorting', 'languageField' => 'sys_language_uid', 'transOrigPointerField' => 'l10n_parent', 'transOrigDiffSourceField' => 'l10n_diffsource', 'delete' => 'deleted', 'enablecolumns' => ['disabled' => 'hidden', 'starttime' => 'starttime', 'endtime' => 'endtime'], 'iconfile' => 'EXT:ppw_si_sync/Resources/Public/Icons/tx_ppwsisync_domain_model_operation.gif'], 'types' => [1 => ['showitem' => 'client_name,entity_name,xml_data,--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access,sys_language_uid,l10n_parent,l10n_diffsource,hidden,starttime,endtime']], 'columns' => ['sys_language_uid' => ['exclude' => 1, 'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.language', 'config' => ['type' => 'select', 'foreign_table' => 'sys_language', 'foreign_table_where' => 'ORDER BY sys_language.title', 'items' => [['LLL:EXT:lang/locallang_general.xlf:LGL.allLanguages', -1], ['LLL:EXT:lang/locallang_general.xlf:LGL.default_value', 0]], 'renderType' => 'selectSingle']], 'l10n_parent' => ['displayCond' => 'FIELD:sys_language_uid:>:0', 'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.l18n_parent', 'config' => ['type' => 'select', 'renderType' => 'selectSingle', 'items' => [['', 0]], 'foreign_table' => 'tx_ppwsisync_domain_model_operation', 'foreign_table_where' => 'AND tx_ppwsisync_domain_model_operation.pid=###CURRENT_PID### AND tx_ppwsisync_domain_model_operation.sys_language_uid IN (-1,0)']], 'l10n_diffsource' => ['config' => ['type' => 'passthrough']], 'hidden' => ['exclude' => 1, 'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden', 'config' => ['type' => 'check']], 'starttime' => ['exclude' => 1, 'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.starttime', 'config' => ['type' => 'input', 'size' => 13, 'eval' => 'datetime', 'checkbox' => 0, 'default' => 0, 'range' => ['lower' => 1536732000], 'behaviour' => ['allowLanguageSynchronization' => true], 'renderType' => 'inputDateTime']], 'endtime' => ['exclude' => 1, 'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.endtime', 'config' => ['type' => 'input', 'size' => 13, 'eval' => 'datetime', 'checkbox' => 0, 'default' => 0, 'range' => ['lower' => 1536732000], 'behaviour' => ['allowLanguageSynchronization' => true], 'renderType' => 'inputDateTime']], 'client_name' => ['exclude' => 1, 'label' => 'LLL:EXT:ppw_si_sync/Resources/Private/Language/locallang_db.xlf:tx_ppwsisync_domain_model_operation.client_name', 'config' => ['type' => 'input', 'size' => 30, 'eval' => 'trim']], 'entity_name' => ['exclude' => 1, 'label' => 'LLL:EXT:ppw_si_sync/Resources/Private/Language/locallang_db.xlf:tx_ppwsisync_domain_model_operation.entity_name', 'config' => ['type' => 'input', 'size' => 30, 'eval' => 'trim']], 'xml_data' => ['exclude' => 1, 'label' => 'LLL:EXT:ppw_si_sync/Resources/Private/Language/locallang_db.xlf:tx_ppwsisync_domain_model_operation.xml_data', 'config' => ['type' => 'text', 'cols' => 40, 'rows' => 15]], 'parent_transaction' => ['config' => ['type' => 'passthrough']]]];
?>

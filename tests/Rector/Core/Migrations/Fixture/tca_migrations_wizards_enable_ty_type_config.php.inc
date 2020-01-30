<?php

defined('TYPO3_MODE') or die();

return [
    'ctrl' => [
    ],
    'columns' => [
        'header_rte' => [
            'config' => [
                'type' => 'text',
                'cols' => '48',
                'rows' => '3',
                'wizards' => [
                    'RTE' => [
                        'notNewRecords' => 1,
                        'RTEonly' => 1,
                        'type' => 'script',
                        'title' => 'LLL:EXT:cms/locallang_ttc.php:bodytext.W.RTE',
                        'icon' => 'wizard_rte2.gif',
                        'script' => 'wizard_rte.php',
                    ],
                ]
            ]
        ],
    ],
];

?>
-----
<?php

defined('TYPO3_MODE') or die();

return ['ctrl' => [], 'columns' => ['header_rte' => ['config' => ['type' => 'text', 'cols' => '48', 'rows' => '3', 'wizards' => ['RTE' => ['notNewRecords' => 1, 'RTEonly' => 1, 'type' => 'script', 'title' => 'LLL:EXT:cms/locallang_ttc.php:bodytext.W.RTE', 'icon' => 'actions-wizard-rte', 'script' => 'wizard_rte.php']]]]]];

?>

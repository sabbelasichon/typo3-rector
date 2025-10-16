<?php

$GLOBALS['TCA']['tx_scheduler_task']['columns']['execution_details']['config']['overrideFieldTca']['frequency']['config']['valuePicker']['items'][] = [
    'value' => '0 2 * * *',
    'label' => 'LLL:EXT:my_extension/Resources/Private/Language/locallang.xlf:daily_2am',
];

<?php

if (isset($GLOBALS['TCA']['tx_scheduler_task'])) {
    $GLOBALS['TCA']['tx_scheduler_task']['types'][\TYPO3\CMS\Scheduler\Task\TableGarbageCollectionTask::class]['taskOptions']['tables'] = [
        'my_table' => [
            'dateField' => 'tstamp',
            'expirePeriod' => 90,
        ],
    ];
}

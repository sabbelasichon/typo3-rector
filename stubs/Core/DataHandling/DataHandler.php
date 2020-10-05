<?php

declare(strict_types=1);

namespace TYPO3\CMS\Core\DataHandling;

if (class_exists(DataHandler::class)) {
    return;
}

class DataHandler
{
    public function rmComma($input): void
    {
    }

    public function newlog2($message, $table, $uid, $pid = null, $error = 0): int
    {
        return 1;
    }

    public function getRecordProperties($table, $id, $noWSOL = false): array
    {
        return [];
    }

    public function log($table, $recuid, $action, $recpid, $error, $details, $details_nr = -1, $data = [], $event_pid = -1, $NEWid = ''): void
    {

    }
}

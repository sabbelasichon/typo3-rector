<?php
declare(strict_types=1);

namespace TYPO3\CMS\Recordlist\RecordList;

if(class_exists('TYPO3\CMS\Recordlist\RecordList\DatabaseRecordList')) {
    return;
}

class DatabaseRecordList
{
    public function thumbCode($row, $table, $field): string
    {
        return '';
    }

    public function requestUri(): string
    {
        return '';
    }

    public function listURL(): string
    {
        return '';
    }

}

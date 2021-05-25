<?php

namespace TYPO3\CMS\Core\Database;

if (class_exists('TYPO3\CMS\Core\Database\ConnectionPool')) {
    return;
}

class ConnectionPool
{
    /**
     * @param string $table
     * @return \TYPO3\CMS\Core\Database\Connection
     */
    public function getConnectionForTable($table)
    {
        $table = (string) $table;
        return new Connection();
    }

    /**
     * @return void
     */
    public function getQueryBuilderForTable($table)
    {
    }
}

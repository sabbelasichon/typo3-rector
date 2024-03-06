<?php

namespace TYPO3\CMS\Core\Database;

use TYPO3\CMS\Core\Database\Query\QueryBuilder;

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
    public function getQueryBuilderForTable($table): QueryBuilder
    {
        return new QueryBuilder();
    }
}

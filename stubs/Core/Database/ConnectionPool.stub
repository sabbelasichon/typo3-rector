<?php

declare(strict_types=1);

namespace TYPO3\CMS\Core\Database;

if (class_exists(ConnectionPool::class)) {
    return;
}

final class ConnectionPool
{
    public function getConnectionForTable(string $table): Connection
    {
        return new Connection();
    }

    public function getQueryBuilderForTable($table): void
    {
    }
}

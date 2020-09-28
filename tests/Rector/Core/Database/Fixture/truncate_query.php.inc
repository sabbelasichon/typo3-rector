<?php

class LegacyDatabaseConnectionTruncateQueryUsage
{
    public function insertData()
    {
        $GLOBALS['TYPO3_DB']->TRUNCATEquery('pages');
    }
}

?>
-----
<?php

class LegacyDatabaseConnectionTruncateQueryUsage
{
    public function insertData()
    {
        $connection = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Database\ConnectionPool::class)->getConnectionForTable('pages');
        $connection->truncate('pages');
    }
}

?>

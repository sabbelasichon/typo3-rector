<?php

class LegacyDatabaseConnectionTruncateQueryUsage
{
    public function insertData()
    {
        $GLOBALS['TYPO3_DB']->exec_TRUNCATEquery('pages');
    }
}

?>
-----
<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
class LegacyDatabaseConnectionTruncateQueryUsage
{
    public function insertData()
    {
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('pages');
        $connection->truncate('pages');
    }
}

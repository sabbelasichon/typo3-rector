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
        GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('pages')->truncate('pages');
    }
}

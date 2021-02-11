<?php

class LegacyDatabaseConnectionUsage
{
    public function insertData()
    {
        $GLOBALS['TYPO3_DB']->exec_INSERTquery(
            'pages',
            [
                'pid' => 0,
                'title' => 'Home',
            ]
        );
    }
}

?>
-----
<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
class LegacyDatabaseConnectionUsage
{
    public function insertData()
    {
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('pages');
        $connection->insert('pages', [
            'pid' => 0,
            'title' => 'Home',
        ]);
    }
}

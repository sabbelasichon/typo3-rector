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

class LegacyDatabaseConnectionUsage
{
    public function insertData()
    {
        $connection = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Database\ConnectionPool::class)->getConnectionForTable('pages');
        $connection->insert('pages', [
            'pid' => 0,
            'title' => 'Home',
        ]);
    }
}

?>

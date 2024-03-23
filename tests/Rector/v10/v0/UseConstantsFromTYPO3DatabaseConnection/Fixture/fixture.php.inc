<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v10\v0\UseConstantsFromTYPO3DatabaseConnection\Fixture;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class MyClass
{
    public function executeQuery(): void
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('table');
        $queryBuilder
            ->select('uid')
            ->from('table')
            ->where(
                $queryBuilder->expr()->eq('bodytext', $queryBuilder->createNamedParameter('lorem', \PDO::PARAM_STR)),
                $queryBuilder->expr()->eq('pid', $queryBuilder->createNamedParameter(42, \PDO::PARAM_INT)),
                $queryBuilder->expr()->eq('available', $queryBuilder->createNamedParameter(true, \PDO::PARAM_BOOL)),
                $queryBuilder->expr()->eq('foo', $queryBuilder->createNamedParameter(null, \PDO::PARAM_NULL))
            )
            ->executeQuery();
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v10\v0\UseConstantsFromTYPO3DatabaseConnection\Fixture;

use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class MyClass
{
    public function executeQuery(): void
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('table');
        $queryBuilder
            ->select('uid')
            ->from('table')
            ->where(
                $queryBuilder->expr()->eq('bodytext', $queryBuilder->createNamedParameter('lorem', Connection::PARAM_STR)),
                $queryBuilder->expr()->eq('pid', $queryBuilder->createNamedParameter(42, Connection::PARAM_INT)),
                $queryBuilder->expr()->eq('available', $queryBuilder->createNamedParameter(true, Connection::PARAM_BOOL)),
                $queryBuilder->expr()->eq('foo', $queryBuilder->createNamedParameter(null, Connection::PARAM_NULL))
            )
            ->executeQuery();
    }
}

?>

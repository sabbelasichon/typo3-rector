<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\MigrateFetchAllToFetchAllAssociativeRector\Fixture;

use Doctrine\DBAL\FetchMode;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;

class MyActionController
{
    private function fetchAll(): void
    {
        $queryBuilder = new QueryBuilder();
        $result = $queryBuilder
            ->select('')
            ->from('')
            ->executeQuery()
            ->fetchAll();
    }

    private function fetchAllAssociative(): void
    {
        $queryBuilder = new QueryBuilder();
        $result = $queryBuilder
            ->select('')
            ->from('')
            ->executeQuery()
            ->fetchAll(FetchMode::ASSOCIATIVE);
    }

    private function fetchAllNumeric(): void
    {
        $queryBuilder = new QueryBuilder();
        $result = $queryBuilder
            ->select('')
            ->from('')
            ->executeQuery()
            ->fetchAll(FetchMode::NUMERIC);
    }

    private function fetchFirstColumn(): void
    {
        $queryBuilder = new QueryBuilder();
        $result = $queryBuilder
            ->select('')
            ->from('')
            ->executeQuery()
            ->fetchAll(FetchMode::COLUMN);
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\MigrateFetchAllToFetchAllAssociativeRector\Fixture;

use Doctrine\DBAL\FetchMode;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;

class MyActionController
{
    private function fetchAll(): void
    {
        $queryBuilder = new QueryBuilder();
        $result = $queryBuilder
            ->select('')
            ->from('')->executeQuery()->fetchAllAssociative();
    }

    private function fetchAllAssociative(): void
    {
        $queryBuilder = new QueryBuilder();
        $result = $queryBuilder
            ->select('')
            ->from('')->executeQuery()->fetchAllAssociative();
    }

    private function fetchAllNumeric(): void
    {
        $queryBuilder = new QueryBuilder();
        $result = $queryBuilder
            ->select('')
            ->from('')->executeQuery()->fetchAllNumeric();
    }

    private function fetchFirstColumn(): void
    {
        $queryBuilder = new QueryBuilder();
        $result = $queryBuilder
            ->select('')
            ->from('')->executeQuery()->fetchFirstColumn();
    }
}

?>

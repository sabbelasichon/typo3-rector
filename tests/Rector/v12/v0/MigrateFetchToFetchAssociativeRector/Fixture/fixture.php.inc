<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\MigrateFetchToFetchAssociativeRector\Fixture;

use Doctrine\DBAL\FetchMode;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;

class MyActionController
{
    private function fetch(): void
    {
        $queryBuilder = new QueryBuilder();
        $result = $queryBuilder
            ->select('')
            ->from('')
            ->executeQuery()
            ->fetch();
    }

    private function fetchAssociative(): void
    {
        $queryBuilder = new QueryBuilder();
        $result = $queryBuilder
            ->select('')
            ->from('')
            ->executeQuery()
            ->fetch(FetchMode::ASSOCIATIVE);
    }

    private function fetchNumeric(): void
    {
        $queryBuilder = new QueryBuilder();
        $result = $queryBuilder
            ->select('')
            ->from('')
            ->executeQuery()
            ->fetch(FetchMode::NUMERIC);
    }

    private function fetchOne(): void
    {
        $queryBuilder = new QueryBuilder();
        $result = $queryBuilder
            ->select('')
            ->from('')
            ->executeQuery()
            ->fetch(FetchMode::COLUMN);
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\MigrateFetchToFetchAssociativeRector\Fixture;

use Doctrine\DBAL\FetchMode;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;

class MyActionController
{
    private function fetch(): void
    {
        $queryBuilder = new QueryBuilder();
        $result = $queryBuilder
            ->select('')
            ->from('')->executeQuery()->fetchAssociative();
    }

    private function fetchAssociative(): void
    {
        $queryBuilder = new QueryBuilder();
        $result = $queryBuilder
            ->select('')
            ->from('')->executeQuery()->fetchAssociative();
    }

    private function fetchNumeric(): void
    {
        $queryBuilder = new QueryBuilder();
        $result = $queryBuilder
            ->select('')
            ->from('')->executeQuery()->fetchNumeric();
    }

    private function fetchOne(): void
    {
        $queryBuilder = new QueryBuilder();
        $result = $queryBuilder
            ->select('')
            ->from('')->executeQuery()->fetchOne();
    }
}

?>

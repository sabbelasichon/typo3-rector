<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\MigrateFetchAllToFetchAllAssociativeRector\Fixture;

use TYPO3\CMS\Core\Database\Query\QueryBuilder;

class MyActionController
{
    private function initArguments(): void
    {
        $queryBuilder = new QueryBuilder();
        $result = $queryBuilder
            ->select('')
            ->from('')
            ->executeQuery()
            ->fetchAll();
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\MigrateFetchAllToFetchAllAssociativeRector\Fixture;

use TYPO3\CMS\Core\Database\Query\QueryBuilder;

class MyActionController
{
    private function initArguments(): void
    {
        $queryBuilder = new QueryBuilder();
        $result = $queryBuilder
            ->select('')
            ->from('')->executeQuery()->fetchAllAssociative();
    }
}

?>

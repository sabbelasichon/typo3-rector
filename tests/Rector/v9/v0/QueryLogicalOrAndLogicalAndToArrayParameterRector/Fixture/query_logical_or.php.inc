<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v9\v0\QueryLogicalOrAndLogicalAndToArrayParameterRector\Fixture;

use TYPO3\CMS\Extbase\Persistence\Repository;

class ProductRepositoryLogicalOr extends Repository
{
    public function findAllForList()
    {
        $query = $this->createQuery();
        $query->matching($query->logicalOr(
            $query->lessThan('foo', 1),
            $query->lessThan('bar', 1)
        ));
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v9\v0\QueryLogicalOrAndLogicalAndToArrayParameterRector\Fixture;

use TYPO3\CMS\Extbase\Persistence\Repository;

class ProductRepositoryLogicalOr extends Repository
{
    public function findAllForList()
    {
        $query = $this->createQuery();
        $query->matching($query->logicalOr([$query->lessThan('foo', 1), $query->lessThan('bar', 1)]));
    }
}

?>

<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\HardenMethodSignatureOfLogicalAndAndLogicalOrRector\Fixtur;

use TYPO3\CMS\Extbase\Persistence\Repository;

class ProductRepositoryLogicalAnd extends Repository
{
    public function findAllForList()
    {
        $query = $this->createQuery();
        $query->matching(
            $query->logicalAnd([
                $query->lessThan('foo', 1),
                $query->lessThan('bar', 1)
            ])
        );
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\HardenMethodSignatureOfLogicalAndAndLogicalOrRector\Fixtur;

use TYPO3\CMS\Extbase\Persistence\Repository;

class ProductRepositoryLogicalAnd extends Repository
{
    public function findAllForList()
    {
        $query = $this->createQuery();
        $query->matching(
            $query->logicalAnd($query->lessThan('foo', 1), $query->lessThan('bar', 1))
        );
    }
}

?>

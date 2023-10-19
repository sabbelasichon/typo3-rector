<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\HardenMethodSignatureOfLogicalAndAndLogicalOrRector\Fixtur;

use TYPO3\CMS\Extbase\Persistence\Repository;

class ProductRepositoryLogicalAndWithConstraintsAsArguments extends Repository
{
    public function findAllForList()
    {
        $constraints = [];
        $query = $this->createQuery();
        if ($constraints !== []) {
            $query->matching($query->logicalAnd(...$constraints));
        }
    }

    public function findAllForListWithDifferentQueryVariableName()
    {
        $constraints = [];
        $q = $this->createQuery();
        if ($constraints !== []) {
            $q->matching($q->logicalAnd(...$constraints));
        }
    }

    public function findAllForListWithLogicalOr()
    {
        $constraints = [];
        $q = $this->createQuery();
        if ($constraints !== []) {
            $q->matching($q->logicalOr(...$constraints));
        }
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\HardenMethodSignatureOfLogicalAndAndLogicalOrRector\Fixtur;

use TYPO3\CMS\Extbase\Persistence\Repository;

class ProductRepositoryLogicalAndWithConstraintsAsArguments extends Repository
{
    public function findAllForList()
    {
        $constraints = [];
        $query = $this->createQuery();
        if (count($constraints) === 1) {
            $query->matching(reset($constraints));
        } elseif (count($constraints) >= 2) {
            $query->matching($query->logicalAnd(...$constraints));
        }
    }

    public function findAllForListWithDifferentQueryVariableName()
    {
        $constraints = [];
        $q = $this->createQuery();
        if (count($constraints) === 1) {
            $q->matching(reset($constraints));
        } elseif (count($constraints) >= 2) {
            $q->matching($q->logicalAnd(...$constraints));
        }
    }

    public function findAllForListWithLogicalOr()
    {
        $constraints = [];
        $q = $this->createQuery();
        if (count($constraints) === 1) {
            $q->matching(reset($constraints));
        } elseif (count($constraints) >= 2) {
            $q->matching($q->logicalOr(...$constraints));
        }
    }
}

?>

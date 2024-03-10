<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\HardenMethodSignatureOfLogicalAndAndLogicalOrRector\Fixture;

use TYPO3\CMS\Extbase\Domain\Model\Demand;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

class BackendUserRepository extends Repository
{
    public function findDemanded(Demand $demand)
    {
        $constraints = [];
        $query = $this->createQuery();
        $query->setOrderings(['userName' => QueryInterface::ORDER_ASCENDING]);
        if ($demand->getUserName() !== '') {
            $searchConstraints = [];
            $searchConstraints[] = $query->like('field', '%test%');
            $searchConstraints[] = $query->equals('uid', (int)$demand->getUserName());
            $constraints[] = $query->logicalOr($searchConstraints);
        }
        if ($demand->getBackendUserGroup()) {
            $constraints[] = $query->logicalOr([
                $query->equals('usergroup', (int)$demand->getBackendUserGroup()),
                $query->like('usergroup', (int)$demand->getBackendUserGroup() . ',%'),
            ]);
        }
        if ($constraints !== []) {
            $query->matching($query->logicalAnd($constraints));
        }
        return $query->execute();
    }
}
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\HardenMethodSignatureOfLogicalAndAndLogicalOrRector\Fixture;

use TYPO3\CMS\Extbase\Domain\Model\Demand;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

class BackendUserRepository extends Repository
{
    public function findDemanded(Demand $demand)
    {
        $constraints = [];
        $query = $this->createQuery();
        $query->setOrderings(['userName' => QueryInterface::ORDER_ASCENDING]);
        if ($demand->getUserName() !== '') {
            $searchConstraints = [];
            $searchConstraints[] = $query->like('field', '%test%');
            $searchConstraints[] = $query->equals('uid', (int)$demand->getUserName());
            if (count($searchConstraints) === 1) {
                $constraints[] = reset($searchConstraints);
            } elseif (count($searchConstraints) >= 2) {
                $constraints[] = $query->logicalOr(...$searchConstraints);
            }
        }
        if ($demand->getBackendUserGroup()) {
            $constraints[] = $query->logicalOr($query->equals('usergroup', (int)$demand->getBackendUserGroup()), $query->like('usergroup', (int)$demand->getBackendUserGroup() . ',%'));
        }
        if (count($constraints) === 1) {
            $query->matching(reset($constraints));
        } elseif (count($constraints) >= 2) {
            $query->matching($query->logicalAnd(...$constraints));
        }
        return $query->execute();
    }
}
?>

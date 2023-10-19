<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\HardenMethodSignatureOfLogicalAndAndLogicalOrRector\Fixture;

use TYPO3\CMS\Extbase\Domain\Model\Constraint;
use TYPO3\CMS\Extbase\Persistence\Generic\Qom\ConstraintInterface;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

class LogEntryRepository extends Repository
{
    public function findByConstraintWithNotEmptyComparison(Constraint $constraint): QueryResultInterface
    {
        $query = $this->createQuery();
        $queryConstraints = $this->createQueryConstraints($query, $constraint);
        if (!empty($queryConstraints)) {
            $query->matching($query->logicalAnd($queryConstraints));
        }
        $query->setOrderings(['uid' => QueryInterface::ORDER_DESCENDING]);
        $query->setLimit($constraint->getNumber());
        return $query->execute();
    }

    public function findByConstraintWithVariableComparison(Constraint $constraint): QueryResultInterface
    {
        $query = $this->createQuery();
        $queryConstraints = $this->createQueryConstraints($query, $constraint);
        if ($queryConstraints) {
            $query->matching($query->logicalAnd($queryConstraints));
        }
        $query->setOrderings(['uid' => QueryInterface::ORDER_DESCENDING]);
        $query->setLimit($constraint->getNumber());
        return $query->execute();
    }

    /**
     * @param QueryInterface $query
     * @param Constraint $constraint
     * @return ConstraintInterface[]
     */
    protected function createQueryConstraints(QueryInterface $query, Constraint $constraint): array
    {
        $queryConstraints = [];
        $queryConstraints[] = $query->greaterThanOrEqual('tstamp', $constraint->getStartTimestamp());
        $queryConstraints[] = $query->lessThan('tstamp', $constraint->getEndTimestamp());
        return $queryConstraints;
    }
}
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\HardenMethodSignatureOfLogicalAndAndLogicalOrRector\Fixture;

use TYPO3\CMS\Extbase\Domain\Model\Constraint;
use TYPO3\CMS\Extbase\Persistence\Generic\Qom\ConstraintInterface;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

class LogEntryRepository extends Repository
{
    public function findByConstraintWithNotEmptyComparison(Constraint $constraint): QueryResultInterface
    {
        $query = $this->createQuery();
        $queryConstraints = $this->createQueryConstraints($query, $constraint);
        if (count($queryConstraints) === 1) {
            $query->matching(reset($queryConstraints));
        } elseif (count($queryConstraints) >= 2) {
            $query->matching($query->logicalAnd(...$queryConstraints));
        }
        $query->setOrderings(['uid' => QueryInterface::ORDER_DESCENDING]);
        $query->setLimit($constraint->getNumber());
        return $query->execute();
    }

    public function findByConstraintWithVariableComparison(Constraint $constraint): QueryResultInterface
    {
        $query = $this->createQuery();
        $queryConstraints = $this->createQueryConstraints($query, $constraint);
        if (count($queryConstraints) === 1) {
            $query->matching(reset($queryConstraints));
        } elseif (count($queryConstraints) >= 2) {
            $query->matching($query->logicalAnd(...$queryConstraints));
        }
        $query->setOrderings(['uid' => QueryInterface::ORDER_DESCENDING]);
        $query->setLimit($constraint->getNumber());
        return $query->execute();
    }

    /**
     * @param QueryInterface $query
     * @param Constraint $constraint
     * @return ConstraintInterface[]
     */
    protected function createQueryConstraints(QueryInterface $query, Constraint $constraint): array
    {
        $queryConstraints = [];
        $queryConstraints[] = $query->greaterThanOrEqual('tstamp', $constraint->getStartTimestamp());
        $queryConstraints[] = $query->lessThan('tstamp', $constraint->getEndTimestamp());
        return $queryConstraints;
    }
}
?>

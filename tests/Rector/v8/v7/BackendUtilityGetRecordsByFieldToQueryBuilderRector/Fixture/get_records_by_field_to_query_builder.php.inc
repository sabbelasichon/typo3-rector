<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v8\v7\BackendUtilityGetRecordsByFieldToQueryBuilderRector\Fixture;

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class BackendUtilityWrapper
{
    public static function getRecordsByFieldWithQueryBuilder($theTable, $theField, $theValue, $whereClause = '', $groupBy = '', $orderBy = '', $limit = '', $useDeleteClause = true): array
    {
        $myQueryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($theTable);
        return BackendUtility::getRecordsByField($theTable, $theField, $theValue, $whereClause, $groupBy, $orderBy, $limit, $useDeleteClause, $myQueryBuilder);
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v8\v7\BackendUtilityGetRecordsByFieldToQueryBuilderRector\Fixture;

use TYPO3\CMS\Core\Database\Query\Restriction\BackendWorkspaceRestriction;
use TYPO3\CMS\Core\Database\Query\Restriction\DeletedRestriction;
use TYPO3\CMS\Core\Database\Query\QueryHelper;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class BackendUtilityWrapper
{
    public static function getRecordsByFieldWithQueryBuilder($theTable, $theField, $theValue, $whereClause = '', $groupBy = '', $orderBy = '', $limit = '', $useDeleteClause = true): array
    {
        $myQueryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($theTable);
        $myQueryBuilder->getRestrictions()->removeAll()->add(GeneralUtility::makeInstance(BackendWorkspaceRestriction::class));
        if ($useDeleteClause) {
            $myQueryBuilder->getRestrictions()->add(GeneralUtility::makeInstance(DeletedRestriction::class));
        }
        $myQueryBuilder->select('*')->from($theTable)->where($myQueryBuilder->expr()->eq($theField, $myQueryBuilder->createNamedParameter($theValue)));
        if ($whereClause) {
            $myQueryBuilder->andWhere(QueryHelper::stripLogicalOperatorPrefix($whereClause));
        }
        if ($groupBy !== '') {
            $myQueryBuilder->groupBy(QueryHelper::parseGroupBy($groupBy));
        }
        if ($orderBy !== '') {
            foreach (QueryHelper::parseOrderBy($orderBy) as $orderPair) {
                list($fieldName, $order) = $orderPair;
                $myQueryBuilder->addOrderBy($fieldName, $order);
            }
        }
        if ($limit !== '') {
            if (strpos($limit, ',')) {
                $limitOffsetAndMax = GeneralUtility::intExplode(',', $limit);
                $myQueryBuilder->setFirstResult((int) $limitOffsetAndMax[0]);
                $myQueryBuilder->setMaxResults((int) $limitOffsetAndMax[1]);
            } else {
                $myQueryBuilder->setMaxResults((int) $limit);
            }
        }
        return $myQueryBuilder->execute()->fetchAll();
    }
}

?>

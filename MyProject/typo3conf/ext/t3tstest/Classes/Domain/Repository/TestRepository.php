<?php

declare(strict_types=1);

namespace Timespin\T3tstest\Domain\Repository;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * This file is part of the "t3tstest" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2022
 */

class TestRepository extends Repository
{

    public function search()
    {
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('table');

        $or = $queryBuilder->expr()->or();

        $or->add($queryBuilder->expr()->eq('znumber', ":search"))
            ->add($queryBuilder->expr()->eq('type', ":search"))
            ->add($queryBuilder->expr()->like('first_name', ":likeSearch"))
            ->add($queryBuilder->expr()->like('last_name', ":likeSearch"))
        ;

        return $queryBuilder->execute();
    }

}


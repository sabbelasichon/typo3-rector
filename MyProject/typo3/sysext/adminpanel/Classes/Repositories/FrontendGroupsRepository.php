<?php

declare(strict_types=1);

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

namespace TYPO3\CMS\Adminpanel\Repositories;

use TYPO3\CMS\Backend\FrontendBackendUserAuthentication;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Type\Bitmask\Permission;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Admin Panel Frontend Groups Repository
 *
 * @internal
 */
class FrontendGroupsRepository
{
    /**
     * returns an array of all available frontend user groups including hidden ones.
     */
    public function getAvailableFrontendUserGroups(): array
    {
        $optionCount = $this->getUserGroupOptionCountByBackendUser($this->getBackendUser());

        $frontendGroups = [];
        if ($optionCount > 0) {
            $frontendGroups = $this->getUserGroupsForPagesByBackendUser($this->getBackendUser());
        }

        return $frontendGroups;
    }

    /**
     * fetches the amount of user groups
     */
    protected function getUserGroupOptionCountByBackendUser(FrontendBackendUserAuthentication $beUser): int
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('fe_groups');

        $optionCount = $queryBuilder->count('fe_groups.uid')
            ->from('fe_groups', 'fe_groups')
            ->innerJoin(
                'fe_groups',
                'pages',
                'pages',
                $queryBuilder->expr()->eq('pages.uid', $queryBuilder->quoteIdentifier('fe_groups.pid'))
            )
            ->where(
                $beUser->getPagePermsClause(Permission::PAGE_SHOW)
            )
            ->executeQuery()
            ->fetchOne();

        return (int)$optionCount;
    }

    /**
     * fetches all frontend user groups, except deleted, for pages
     */
    protected function getUserGroupsForPagesByBackendUser(FrontendBackendUserAuthentication $beUser): array
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('fe_groups');

        return $queryBuilder->select('fe_groups.uid', 'fe_groups.title')
            ->from('fe_groups')
            ->innerJoin(
                'fe_groups',
                'pages',
                'pages',
                $queryBuilder->expr()->eq('pages.uid', $queryBuilder->quoteIdentifier('fe_groups.pid'))
            )
            ->where(
                $beUser->getPagePermsClause(Permission::PAGE_SHOW)
            )
            ->orderBy('fe_groups.title')
            ->executeQuery()
            ->fetchAllAssociative();
    }

    protected function getBackendUser(): FrontendBackendUserAuthentication
    {
        return $GLOBALS['BE_USER'];
    }
}

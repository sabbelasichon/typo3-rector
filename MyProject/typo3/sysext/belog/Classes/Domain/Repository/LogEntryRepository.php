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

namespace TYPO3\CMS\Belog\Domain\Repository;

use Psr\Log\LogLevel;
use TYPO3\CMS\Backend\Tree\Repository\PageTreeRepository;
use TYPO3\CMS\Belog\Domain\Model\Constraint;
use TYPO3\CMS\Belog\Domain\Model\LogEntry;
use TYPO3\CMS\Core\Authentication\GroupResolver;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Log\LogLevel as Typo3LogLevel;
use TYPO3\CMS\Core\Type\Bitmask\Permission;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\Qom\ConstraintInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\QuerySettingsInterface;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * Sys log entry repository
 * @internal This class is a TYPO3 Backend implementation and is not considered part of the Public TYPO3 API.
 * @extends Repository<LogEntry>
 */
class LogEntryRepository extends Repository
{
    public ?QuerySettingsInterface $querySettings = null;

    public function injectQuerySettings(QuerySettingsInterface $querySettings): void
    {
        $this->querySettings = $querySettings;
    }

    /**
     * Initialize some local variables to be used during creation of objects
     */
    public function initializeObject(): void
    {
        $this->setDefaultQuerySettings($this->querySettings->setRespectStoragePage(false));
    }

    /**
     * Finds all log entries that match all given constraints.
     */
    public function findByConstraint(Constraint $constraint): QueryResultInterface
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
     * Create an array of query constraints from constraint object
     *
     * @return ConstraintInterface[]
     */
    protected function createQueryConstraints(QueryInterface $query, Constraint $constraint): array
    {
        $queryConstraints = [];
        // User / group handling
        $this->addUsersAndGroupsToQueryConstraints($constraint, $query, $queryConstraints);
        // Workspace
        if ($constraint->getWorkspaceUid() !== -99) {
            $queryConstraints[] = $query->equals('workspace', $constraint->getWorkspaceUid());
        }
        // Channel
        if ($channel = $constraint->getChannel()) {
            $queryConstraints[] = $query->equals('channel', $channel);
        }
        // Level
        if ($level = $constraint->getLevel()) {
            $queryConstraints[] = $query->in('level', Typo3LogLevel::atLeast($level));
        }
        // Start / endtime handling: The timestamp calculation was already done
        // in the controller, since we need those calculated values in the view as well.
        $queryConstraints[] = $query->greaterThanOrEqual('tstamp', $constraint->getStartTimestamp());
        $queryConstraints[] = $query->lessThan('tstamp', $constraint->getEndTimestamp());
        // Page and level constraint if in page context
        $this->addPageTreeConstraintsToQuery($constraint, $query, $queryConstraints);
        return $queryConstraints;
    }

    /**
     * Adds constraints for the page(s) to the query; this could be one single page or a whole subtree beneath a given
     * page.
     */
    protected function addPageTreeConstraintsToQuery(
        Constraint $constraint,
        QueryInterface $query,
        array &$queryConstraints
    ): void {
        $pageIds = [];
        // Check if we should get a whole tree of pages and not only a single page
        if ($constraint->getDepth() > 0) {
            $repository = GeneralUtility::makeInstance(PageTreeRepository::class);
            $repository->setAdditionalWhereClause($GLOBALS['BE_USER']->getPagePermsClause(Permission::PAGE_SHOW));
            $pages = $repository->getFlattenedPages([$constraint->getPageId()], $constraint->getDepth());
            foreach ($pages as $page) {
                $pageIds[] = (int)$page['uid'];
            }
        }
        if (!empty($constraint->getPageId())) {
            $pageIds[] = $constraint->getPageId();
        }
        if (!empty($pageIds)) {
            $queryConstraints[] = $query->in('eventPid', $pageIds);
        }
    }

    /**
     * Adds users and groups to the query constraints.
     */
    protected function addUsersAndGroupsToQueryConstraints(
        Constraint $constraint,
        QueryInterface $query,
        array &$queryConstraints
    ): void {
        $userOrGroup = $constraint->getUserOrGroup();
        if ($userOrGroup === '') {
            return;
        }
        // Constraint for a group
        if (str_starts_with($userOrGroup, 'gr-')) {
            $groupId = (int)substr($userOrGroup, 3);
            $groupResolver = GeneralUtility::makeInstance(GroupResolver::class);
            $userIds = $groupResolver->findAllUsersInGroups([$groupId], 'be_groups', 'be_users');
            if (!empty($userIds)) {
                $userIds = array_column($userIds, 'uid');
                $userIds = array_map('intval', $userIds);
                $queryConstraints[] = $query->in('userid', $userIds);
            } else {
                // If there are no group members -> use -1 as constraint to not find anything
                $queryConstraints[] = $query->in('userid', [-1]);
            }
        } elseif (str_starts_with($userOrGroup, 'us-')) {
            $queryConstraints[] = $query->equals('userid', (int)substr($userOrGroup, 3));
        } elseif ($userOrGroup === '-1') {
            $queryConstraints[] = $query->equals('userid', (int)$GLOBALS['BE_USER']->user['uid']);
        }
    }

    /**
     * Deletes all messages which have the same message details
     */
    public function deleteByMessageDetails(LogEntry $logEntry): int
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('sys_log');
        $constraints = [];
        $constraints[] = $queryBuilder->expr()->eq('details', $queryBuilder->createNamedParameter($logEntry->getDetails()));
        // If the detailsNo is 11 or 12 we got messages that are heavily using placeholders. In this case
        // we need to compare both the message and the actual log data to not remove too many log entries.
        if (GeneralUtility::inList('11,12', (string)$logEntry->getDetailsNumber())) {
            $constraints[] = $queryBuilder->expr()->eq('log_data', $queryBuilder->createNamedParameter($logEntry->getLogData()));
        }
        return (int)$queryBuilder->delete('sys_log')
            ->where(...$constraints)
            ->executeStatement();
    }

    public function getUsedChannels(): array
    {
        $conn = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('sys_log');

        $channels = $conn->createQueryBuilder()
            ->select('channel')
            ->distinct()
            ->from('sys_log')
            ->orderBy('channel')
            ->executeQuery()
            ->fetchFirstColumn();

        return array_combine($channels, $channels);
    }

    public function getUsedLevels(): array
    {
        static $allLevels = [
            LogLevel::EMERGENCY,
            LogLevel::ALERT,
            LogLevel::CRITICAL,
            LogLevel::ERROR,
            LogLevel::WARNING,
            LogLevel::NOTICE,
            LogLevel::INFO,
            LogLevel::DEBUG,
        ];

        $conn = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('sys_log');

        $levels = $conn->createQueryBuilder()
            ->select('level')
            ->distinct()
            ->from('sys_log')
            ->executeQuery()
            ->fetchFirstColumn();

        $levelsUsed = array_intersect($allLevels, $levels);

        return array_combine($levelsUsed, $levelsUsed);
    }
}

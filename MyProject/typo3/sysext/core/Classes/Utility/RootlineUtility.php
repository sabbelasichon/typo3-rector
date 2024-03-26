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

namespace TYPO3\CMS\Core\Utility;

use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Cache\Frontend\FrontendInterface;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Database\Query\Restriction\DeletedRestriction;
use TYPO3\CMS\Core\Database\RelationHandler;
use TYPO3\CMS\Core\Domain\Repository\PageRepository;
use TYPO3\CMS\Core\Exception\Page\BrokenRootLineException;
use TYPO3\CMS\Core\Exception\Page\CircularRootLineException;
use TYPO3\CMS\Core\Exception\Page\MountPointsDisabledException;
use TYPO3\CMS\Core\Exception\Page\PageNotFoundException;
use TYPO3\CMS\Core\Exception\Page\PagePropertyRelationNotFoundException;
use TYPO3\CMS\Core\Versioning\VersionState;

/**
 * A utility resolving and Caching the Rootline generation
 */
class RootlineUtility
{
    /** @internal */
    public const RUNTIME_CACHE_TAG = 'rootline-utility';

    protected int $pageUid;

    protected string $mountPointParameter;

    /** @var int[] */
    protected array $parsedMountPointParameters = [];

    protected int $languageUid = 0;

    protected int $workspaceUid = 0;

    protected FrontendInterface $cache;
    protected FrontendInterface $runtimeCache;

    /**
     * Default fields to fetch when populating rootline data, will be merged dynamically
     * with $GLOBALS['TYPO3_CONF_VARS']['FE']['addRootLineFields'] in getRecordArray().
     *
     * @see self::getRecordArray()
     * @var string[]
     */
    protected array $rootlineFields = [
        'pid',
        'uid',
        't3ver_oid',
        't3ver_wsid',
        't3ver_state',
        'title',
        'nav_title',
        'media',
        'layout',
        'hidden',
        'starttime',
        'endtime',
        'fe_group',
        'extendToSubpages',
        'doktype',
        'TSconfig',
        'tsconfig_includes',
        'is_siteroot',
        'mount_pid',
        'mount_pid_ol',
        'backend_layout_next_level',
    ];

    /**
     * Database Query Object
     */
    protected PageRepository $pageRepository;

    /**
     * Query context
     */
    protected Context $context;

    protected string $cacheIdentifier;

    /**
     * @throws MountPointsDisabledException
     */
    public function __construct(int $uid, string $mountPointParameter = '', ?Context $context = null)
    {
        $this->mountPointParameter = $this->sanitizeMountPointParameter($mountPointParameter);
        $this->context = $context ?? GeneralUtility::makeInstance(Context::class);
        $this->pageRepository = GeneralUtility::makeInstance(PageRepository::class, $context);

        $this->languageUid = $this->context->getPropertyFromAspect('language', 'id', 0);
        $this->workspaceUid = (int)$this->context->getPropertyFromAspect('workspace', 'id', 0);
        if ($this->mountPointParameter !== '') {
            if (!($GLOBALS['TYPO3_CONF_VARS']['FE']['enable_mount_pids'] ?? false)) {
                throw new MountPointsDisabledException('Mount-Point Pages are disabled for this installation. Cannot resolve a Rootline for a page with Mount-Points', 1343462896);
            }
            $this->parseMountPointParameter();
        }

        $this->pageUid = $this->resolvePageId($uid);
        $this->cache ??= GeneralUtility::makeInstance(CacheManager::class)->getCache('rootline');
        $this->runtimeCache ??= GeneralUtility::makeInstance(CacheManager::class)->getCache('runtime');

        $this->cacheIdentifier = $this->getCacheIdentifier();
    }

    /**
     * Constructs the cache Identifier
     */
    public function getCacheIdentifier(int $otherUid = null): string
    {
        $mountPointParameter = (string)$this->mountPointParameter;
        if ($mountPointParameter !== '' && str_contains($mountPointParameter, ',')) {
            $mountPointParameter = str_replace(',', '__', $mountPointParameter);
        }

        return implode('_', [
            $otherUid ?? $this->pageUid,
            $mountPointParameter,
            $this->languageUid,
            $this->workspaceUid,
            $this->context->getAspect('visibility')->includeHiddenContent() ? '1' : '0',
            $this->context->getAspect('visibility')->includeHiddenPages() ? '1' : '0',
        ]);
    }

    /**
     * Returns the actual rootline without the tree root (uid=0), including the page with $this->pageUid
     */
    public function get(): array
    {
        if ($this->pageUid === 0) {
            // pageUid 0 has no root line, return empty array right away
            return [];
        }
        if (!$this->runtimeCache->has('rootline-localcache-' . $this->cacheIdentifier)) {
            $entry = $this->cache->get($this->cacheIdentifier);
            if (!$entry) {
                $this->generateRootlineCache();
            } else {
                $this->runtimeCache->set('rootline-localcache-' . $this->cacheIdentifier, $entry, [self::RUNTIME_CACHE_TAG]);
                $depth = count($entry);
                // Populate the root-lines for parent pages as well
                // since they are part of the current root-line
                while ($depth > 1) {
                    --$depth;
                    $parentCacheIdentifier = $this->getCacheIdentifier($entry[$depth - 1]['uid']);
                    // Abort if the root-line of the parent page is
                    // already in the local cache data
                    if ($this->runtimeCache->has('rootline-localcache-' . $parentCacheIdentifier)) {
                        break;
                    }
                    // Behaves similar to array_shift(), but preserves
                    // the array keys - which contain the page ids here
                    $entry = array_slice($entry, 1, null, true);
                    $this->runtimeCache->set('rootline-localcache-' . $parentCacheIdentifier, $entry, [self::RUNTIME_CACHE_TAG]);
                }
            }
        }
        return $this->runtimeCache->get('rootline-localcache-' . $this->cacheIdentifier);
    }

    /**
     * Queries the database for the page record and returns it.
     *
     * @param int $uid Page id
     * @throws PageNotFoundException
     * @return array<string, string|int|float|null>
     *   The array will contain the fields listed in the $rootlineFields static property.
     */
    protected function getRecordArray(int $uid): array
    {
        $rootlineFields = array_merge($this->rootlineFields, GeneralUtility::trimExplode(',', $GLOBALS['TYPO3_CONF_VARS']['FE']['addRootLineFields'], true));
        $rootlineFields = array_unique($rootlineFields);
        $currentCacheIdentifier = $this->getCacheIdentifier($uid);
        if (!$this->runtimeCache->has('rootline-recordcache-' . $currentCacheIdentifier)) {
            $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('pages');
            $queryBuilder->getRestrictions()->removeAll()->add(GeneralUtility::makeInstance(DeletedRestriction::class));
            $row = $queryBuilder->select(...$rootlineFields)
                ->from('pages')
                ->where(
                    $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($uid, Connection::PARAM_INT)),
                    $queryBuilder->expr()->in('t3ver_wsid', $queryBuilder->createNamedParameter([0, $this->workspaceUid], Connection::PARAM_INT_ARRAY))
                )
                ->executeQuery()
                ->fetchAssociative();
            if (empty($row)) {
                throw new PageNotFoundException('Could not fetch page data for uid ' . $uid . '.', 1343589451);
            }
            $this->pageRepository->versionOL('pages', $row, false, true);
            if (is_array($row)) {
                $row = $this->pageRepository->getLanguageOverlay('pages', $row, $this->context->getAspect('language'));
                $row = $this->enrichWithRelationFields($row['_PAGES_OVERLAY_UID'] ??  $uid, $row);
                $this->runtimeCache->set('rootline-recordcache-' . $currentCacheIdentifier, $row, [self::RUNTIME_CACHE_TAG]);
            }
        }
        if (!is_array($this->runtimeCache->get('rootline-recordcache-' . $currentCacheIdentifier) ?? false)) {
            throw new PageNotFoundException('Broken rootline. Could not resolve page with uid ' . $uid . '.', 1343464101);
        }
        return $this->runtimeCache->get('rootline-recordcache-' . $currentCacheIdentifier);
    }

    /**
     * Resolve relations as defined in TCA and add them to the provided $pageRecord array.
     *
     * @param int $uid page ID
     * @param array<string, string|int|float|null> $pageRecord Page record (possibly overlaid) to be extended with relations
     * @throws PagePropertyRelationNotFoundException
     * @return array<string, string|int|float|null> $pageRecord with additional relations
     */
    protected function enrichWithRelationFields(int $uid, array $pageRecord): array
    {
        if (!isset($GLOBALS['TCA']['pages']['columns']) || !is_array($GLOBALS['TCA']['pages']['columns'])) {
            return $pageRecord;
        }

        foreach ($GLOBALS['TCA']['pages']['columns'] as $column => $configuration) {
            // Ensure that only fields defined in $rootlineFields (and "addRootLineFields") are actually evaluated
            if (array_key_exists($column, $pageRecord) && $this->columnHasRelationToResolve($configuration)) {
                $fieldConfig = $configuration['config'];
                $relatedUids = [];
                if (($fieldConfig['MM'] ?? false) || (!empty($fieldConfig['foreign_table'] ?? $fieldConfig['allowed'] ?? ''))) {
                    $relationHandler = GeneralUtility::makeInstance(RelationHandler::class);
                    // do not include hidden relational fields
                    $relationalTable = $fieldConfig['foreign_table'] ?? $fieldConfig['allowed'];
                    $hiddenFieldName = $GLOBALS['TCA'][$relationalTable]['ctrl']['enablecolumns']['disabled'] ?? null;
                    if (!$this->context->getAspect('visibility')->includeHiddenContent() && $hiddenFieldName) {
                        $fieldConfig['foreign_match_fields'][$hiddenFieldName] = 0;
                    }
                    $relationHandler->setWorkspaceId($this->workspaceUid);
                    $relationHandler->start(
                        $pageRecord[$column],
                        $fieldConfig['foreign_table'] ?? $fieldConfig['allowed'],
                        $fieldConfig['MM'] ?? '',
                        $uid,
                        'pages',
                        $fieldConfig
                    );
                    $relatedUids = $relationHandler->getValueArray();
                }
                $pageRecord[$column] = implode(',', $relatedUids);
            }
        }
        return $pageRecord;
    }

    /**
     * Checks whether the TCA Configuration array of a column
     * describes a relation which is not stored as CSV in the record
     *
     * @param array $configuration TCA configuration to check
     * @return bool TRUE, if it describes a non-CSV relation
     */
    protected function columnHasRelationToResolve(array $configuration): bool
    {
        $configuration = $configuration['config'] ?? [];
        if (!empty($configuration['MM']) && !empty($configuration['type']) && in_array($configuration['type'], ['select', 'inline', 'group'])) {
            return true;
        }
        if (!empty($configuration['foreign_field']) && !empty($configuration['type']) && in_array($configuration['type'], ['select', 'inline', 'file'])) {
            return true;
        }
        if (($configuration['type'] ?? '') === 'category' && ($configuration['relationship'] ?? '') === 'manyToMany') {
            return true;
        }
        return false;
    }

    /**
     * Actual function to generate the rootline and cache it
     *
     * @throws CircularRootLineException
     */
    protected function generateRootlineCache(): void
    {
        $page = $this->getRecordArray($this->pageUid);
        // If the current page is a mounted (according to the MP parameter) handle the mount-point
        if ($this->isMountedPage()) {
            $mountPoint = $this->getRecordArray($this->parsedMountPointParameters[$this->pageUid]);
            $page = $this->processMountedPage($page, $mountPoint);
            $parentUid = $mountPoint['pid'];
            // Anyhow after reaching the mount-point, we have to go up that rootline
            unset($this->parsedMountPointParameters[$this->pageUid]);
        } else {
            $parentUid = $page['pid'];
        }
        $cacheTags = ['pageId_' . $page['uid']];
        if ($parentUid > 0) {
            // Get rootline of (and including) parent page
            $mountPointParameter = !empty($this->parsedMountPointParameters) ? $this->mountPointParameter : '';
            $rootlineUtility = GeneralUtility::makeInstance(self::class, $parentUid, $mountPointParameter, $this->context);
            $rootline = $rootlineUtility->get();
            // retrieve cache tags of parent rootline
            foreach ($rootline as $entry) {
                $cacheTags[] = 'pageId_' . $entry['uid'];
                if ($entry['uid'] == $this->pageUid) {
                    throw new CircularRootLineException('Circular connection in rootline for page with uid ' . $this->pageUid . ' found. Check your mountpoint configuration.', 1343464103);
                }
            }
        } else {
            $rootline = [];
        }
        $rootline[] = $page;
        krsort($rootline);
        $this->cache->set($this->cacheIdentifier, $rootline, $cacheTags);
        $this->runtimeCache->set('rootline-localcache-' . $this->cacheIdentifier, $rootline, [self::RUNTIME_CACHE_TAG]);
    }

    /**
     * Checks whether the current Page is a Mounted Page
     * (according to the MP-URL-Parameter)
     */
    public function isMountedPage(): bool
    {
        return array_key_exists($this->pageUid, $this->parsedMountPointParameters);
    }

    /**
     * Enhances with mount point information or replaces the node if needed
     *
     * @param array<string, string|int|float|null> $mountedPageData page record array of mounted page
     * @param array<string, string|int|float|null> $mountPointPageData page record array of mount point page
     * @throws BrokenRootLineException
     * @return array<string, string|int|float|null>
     */
    protected function processMountedPage(array $mountedPageData, array $mountPointPageData): array
    {
        $mountPid = $mountPointPageData['mount_pid'] ?? null;
        $uid = $mountedPageData['uid'] ?? null;
        if ((int)$mountPid !== (int)$uid) {
            throw new BrokenRootLineException('Broken rootline. Mountpoint parameter does not match the actual rootline. mount_pid (' . $mountPid . ') does not match page uid (' . $uid . ').', 1343464100);
        }
        // Current page replaces the original mount-page
        $mountUid = $mountPointPageData['uid'] ?? null;
        if (!empty($mountPointPageData['mount_pid_ol'])) {
            $mountedPageData['_MOUNT_OL'] = true;
            $mountedPageData['_MOUNT_PAGE'] = [
                'uid' => $mountUid,
                'pid' => $mountPointPageData['pid'] ?? null,
                'title' => $mountPointPageData['title'] ?? null,
            ];
        } else {
            // The mount-page is not replaced, the mount-page itself has to be used
            $mountedPageData = $mountPointPageData;
        }
        $mountedPageData['_MOUNTED_FROM'] = $this->pageUid;
        $mountedPageData['_MP_PARAM'] = $this->pageUid . '-' . $mountUid;
        return $mountedPageData;
    }

    /**
     * Sanitize the MountPoint Parameter
     * Splits the MP-Param via "," and removes mountpoints
     * that don't have the format \d+-\d+
     */
    protected function sanitizeMountPointParameter(string $mountPointParameter): string
    {
        $mountPointParameter = trim($mountPointParameter);
        if ($mountPointParameter === '') {
            return '';
        }
        $mountPoints = GeneralUtility::trimExplode(',', $mountPointParameter);
        foreach ($mountPoints as $key => $mP) {
            // If MP has incorrect format, discard it
            if (!preg_match('/^\d+-\d+$/', $mP)) {
                unset($mountPoints[$key]);
            }
        }
        return implode(',', $mountPoints);
    }

    /**
     * Parse the MountPoint Parameters
     * Splits the MP-Param via "," for several nested mountpoints
     * and afterwords registers the mountpoint configurations
     */
    protected function parseMountPointParameter(): void
    {
        $mountPoints = GeneralUtility::trimExplode(',', $this->mountPointParameter);
        foreach ($mountPoints as $mP) {
            [$mountedPageUid, $mountPageUid] = GeneralUtility::intExplode('-', $mP);
            $this->parsedMountPointParameters[$mountedPageUid] = $mountPageUid;
        }
    }

    /**
     * Fetches the UID of the page.
     *
     * If the page was moved in a workspace, actually returns the UID
     * of the moved version in the workspace.
     */
    protected function resolvePageId(int $pageId): int
    {
        if ($pageId === 0 || $this->workspaceUid === 0) {
            return $pageId;
        }

        $page = $this->resolvePageRecord($pageId);
        if (!isset($page['t3ver_state']) || !VersionState::cast($page['t3ver_state'])->equals(VersionState::MOVE_POINTER)) {
            return $pageId;
        }

        $movePointerId = $this->resolveMovePointerId((int)$page['t3ver_oid']);
        return $movePointerId ?: $pageId;
    }

    protected function resolvePageRecord(int $pageId): ?array
    {
        $queryBuilder = $this->createQueryBuilder('pages');
        $queryBuilder->getRestrictions()->removeAll()
            ->add(GeneralUtility::makeInstance(DeletedRestriction::class));

        $statement = $queryBuilder
            ->from('pages')
            ->select('uid', 't3ver_oid', 't3ver_state')
            ->where(
                $queryBuilder->expr()->eq(
                    'uid',
                    $queryBuilder->createNamedParameter($pageId, Connection::PARAM_INT)
                )
            )
            ->setMaxResults(1)
            ->executeQuery();

        $record = $statement->fetchAssociative();
        return $record ?: null;
    }

    /**
     * Fetched the UID of the versioned record if the live record has been moved in a workspace.
     */
    protected function resolveMovePointerId(int $liveId): ?int
    {
        $queryBuilder = $this->createQueryBuilder('pages');
        $queryBuilder->getRestrictions()->removeAll()
            ->add(GeneralUtility::makeInstance(DeletedRestriction::class));

        $statement = $queryBuilder
            ->from('pages')
            ->select('uid')
            ->setMaxResults(1)
            ->where(
                $queryBuilder->expr()->eq(
                    't3ver_wsid',
                    $queryBuilder->createNamedParameter($this->workspaceUid, Connection::PARAM_INT)
                ),
                $queryBuilder->expr()->eq(
                    't3ver_state',
                    $queryBuilder->createNamedParameter(VersionState::MOVE_POINTER, Connection::PARAM_INT)
                ),
                $queryBuilder->expr()->eq(
                    't3ver_oid',
                    $queryBuilder->createNamedParameter($liveId, Connection::PARAM_INT)
                )
            )
            ->executeQuery();

        $movePointerId = $statement->fetchOne();
        return $movePointerId ? (int)$movePointerId : null;
    }

    protected function createQueryBuilder(string $tableName): QueryBuilder
    {
        return GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable($tableName);
    }
}

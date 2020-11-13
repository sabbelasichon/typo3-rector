<?php

declare(strict_types=1);

namespace TYPO3\CMS\Frontend\Page;

if (class_exists(PageRepository::class)) {
    return;
}

final class PageRepository
{
    /**
     * @var int
     */
    public $versioningWorkspaceId = 0;

    public function enableFields($table, $show_hidden = -1, $ignore_array = [], $noVersionPreview = false): void
    {
    }

    public function init($show_hidden): string
    {
        return 'foo';
    }

    public function getPageShortcut($SC, $mode, $thisUid, int $itera, array $pageLog, bool $disableGroupCheck): array
    {
        return [];
    }

    public function getFirstWebPage($uid): array
    {
        return [];
    }

    public function getMenu($pageId, $fields = '*', $sortField = 'sorting', $additionalWhereClause = '', $checkShortcuts = true): array
    {
        return [];
    }
}

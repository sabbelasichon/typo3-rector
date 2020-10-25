<?php
declare(strict_types=1);

namespace TYPO3\CMS\Core\Site;

use TYPO3\CMS\Core\Site\Entity\Site;

if (class_exists(SiteFinder::class)) {
    return;
}

final class SiteFinder
{
    public function getSiteByPageId(int $pageId, array $rootLine = null, string $mountPointParameter = null): Site
    {
        return new Site();
    }
}

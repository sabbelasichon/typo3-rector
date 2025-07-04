<?php

namespace TYPO3\CMS\Core\Domain\Repository;

if (class_exists('TYPO3\CMS\Core\Domain\Repository\PageRepository')) {
    return;
}

final class PageRepository
{
    public const DOKTYPE_DEFAULT = 1;
    public const DOKTYPE_LINK = 3;
    public const DOKTYPE_SHORTCUT = 4;
    public const DOKTYPE_BE_USER_SECTION = 6;
    public const DOKTYPE_MOUNTPOINT = 7;
    public const DOKTYPE_SPACER = 199;
    public const DOKTYPE_SYSFOLDER = 254;
    public const DOKTYPE_RECYCLER = 255;

    /**
     * @return array
     */
    public function getPage($uid, $disableGroupAccessCheck = false)
    {
        return [];
    }
}

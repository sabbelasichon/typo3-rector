<?php

namespace TYPO3\CMS\Core\Domain\Repository;

if (class_exists('TYPO3\CMS\Core\Domain\Repository\PageRepository')) {
    return;
}

final class PageRepository
{
    /**
     * @return array
     */
    public function getPage($uid, $disableGroupAccessCheck = false)
    {
        return [];
    }
}

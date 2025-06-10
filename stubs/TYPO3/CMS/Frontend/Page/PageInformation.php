<?php

namespace TYPO3\CMS\Frontend\Page;

if (class_exists('TYPO3\CMS\Frontend\Page\PageInformation')) {
    return;
}

final class PageInformation
{
    public function getId(): int
    {
        return 1;
    }

    public function getRootLine(): array
    {
        return [];
    }

    public function getPageRecord(): array
    {
        return [];
    }

    public function getContentFromPid(): int
    {
        return 1;
    }
}

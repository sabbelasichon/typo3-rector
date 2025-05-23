<?php

namespace TYPO3\CMS\Core\Routing;

if (class_exists('TYPO3\CMS\Core\Routing\PageArguments')) {
    return;
}

class PageArguments
{
    protected string $pageType;

    public function getPageType(): string
    {
        return $this->pageType;
    }
}

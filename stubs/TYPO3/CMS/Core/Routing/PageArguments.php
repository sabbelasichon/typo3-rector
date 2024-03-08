<?php

declare(strict_types=1);

namespace TYPO3\CMS\Core\Routing;

class PageArguments
{
    protected string $pageType;

    public function getPageType(): string
    {
        return $this->pageType;
    }
}

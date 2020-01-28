<?php

declare(strict_types=1);

namespace TYPO3\CMS\Frontend\Page;

if (class_exists(PageRepository::class)) {
    return;
}

final class PageRepository
{
    public function enableFields($table, $show_hidden = -1, $ignore_array = [], $noVersionPreview = false): void
    {
    }
}

<?php

declare(strict_types=1);

namespace TYPO3\CMS\Frontend\ContentObject;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Page\PageRepository;

final class ContentObjectRenderer
{
    public function RECORDS(array $config): void
    {
        $this->cObjGetSingle('RECORDS', $config);
    }

    public function cObjGetSingle(string $string, array $config): void
    {
    }

    public function enableFields($table, $show_hidden = false, array $ignore_array = [])
    {
        return GeneralUtility::makeInstance(PageRepository::class)->enableFields($table, $show_hidden ? true : -1, $ignore_array);
    }
}

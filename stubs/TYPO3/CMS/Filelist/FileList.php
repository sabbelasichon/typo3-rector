<?php

namespace TYPO3\CMS\Filelist;

use TYPO3\CMS\Core\Resource\Folder;
use TYPO3\CMS\Filelist\Type\SortDirection;

if (class_exists('TYPO3\CMS\Filelist\FileList')) {
    return;
}

class FileList
{
    /**
     * @param Folder $folderObject The folder to work on
     * @param int $currentPage The current page to render
     * @param string $sortField Sorting column
     * @param bool|SortDirection $sortDirection Sorting direction
     * @param mixed $mode Mode of the file list
     */
    public function start($folderObject, int $currentPage, string $sortField, bool $sortDirection, $mode)
    {
    }
}

<?php


namespace TYPO3\CMS\Core\Resource\OnlineMedia\Helpers;

use TYPO3\CMS\Core\Resource\File;

if (interface_exists('TYPO3\CMS\Core\Resource\OnlineMedia\Helpers\OnlineMediaHelperInterface')) {
    return;
}

interface OnlineMediaHelperInterface
{
    public function getPublicUrl(File $file, $relativeToCurrentScript = false);
}

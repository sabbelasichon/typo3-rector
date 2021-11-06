<?php

namespace TYPO3\CMS\Core\Resource;

if (class_exists('TYPO3\CMS\Core\Resource\FileReference')) {
    return;
}

class FileReference
{
    /**
     * @return string
     */
    public function getPublicUrl($relativeToCurrentScript = false)
    {
        return 'foo';
    }
}

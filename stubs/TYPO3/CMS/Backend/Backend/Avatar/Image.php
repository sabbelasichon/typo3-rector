<?php

namespace TYPO3\CMS\Backend\Backend\Avatar;

if (class_exists('TYPO3\CMS\Backend\Backend\Avatar\Image')) {
    return;
}

class Image
{
    /**
     * @param bool $relativeToCurrentScript Determines whether the URL returned should be relative to the current script, in case it is relative at all.
     * @return string
     */
    public function getUrl($relativeToCurrentScript = false)
    {
        return '';
    }
}

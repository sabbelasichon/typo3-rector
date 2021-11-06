<?php

namespace TYPO3\CMS\Core\Resource;

if (class_exists('TYPO3\CMS\Core\Resource\Event\GeneratePublicUrlForResourceEvent')) {
    return;
}

class GeneratePublicUrlForResourceEvent
{
    /**
     * @return bool
     */
    public function isRelativeToCurrentScript()
    {
        return true;
    }
}

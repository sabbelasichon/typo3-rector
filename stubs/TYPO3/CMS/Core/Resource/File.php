<?php

declare(strict_types=1);

namespace TYPO3\CMS\Core\Resource;

if (class_exists('TYPO3\CMS\Core\Resource\File')) {
    return;
}

class File
{
    public function _getMetaData(): string
    {
        return 'foo';
    }

    public function getMetaData(): MetaDataAspect
    {
        return new MetaDataAspect();
    }
}

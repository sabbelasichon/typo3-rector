<?php


namespace TYPO3\CMS\Core\Resource;

if (class_exists(File::class)) {
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

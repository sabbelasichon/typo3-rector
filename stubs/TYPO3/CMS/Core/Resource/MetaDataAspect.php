<?php


namespace TYPO3\CMS\Core\Resource;

if (class_exists(MetaDataAspect::class)) {
    return;
}

class MetaDataAspect
{
    public function get(): string
    {
        return 'foo';
    }
}

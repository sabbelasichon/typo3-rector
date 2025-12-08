<?php

namespace TYPO3\CMS\Core\Resource\Collection;

if (class_exists('TYPO3\CMS\Core\Resource\Collection\FileCollectionRegistry')) {
    return;
}

class FileCollectionRegistry
{
    public function addTypeToTCA($type, $label, $availableFields, array $additionalColumns = [])
    {
    }
}

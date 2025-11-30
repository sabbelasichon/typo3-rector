<?php

namespace TYPO3\CMS\Extbase\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

if (class_exists('TYPO3\CMS\Extbase\Domain\Model\FileReference')) {
    return;
}

class FileReference extends AbstractEntity
{
}

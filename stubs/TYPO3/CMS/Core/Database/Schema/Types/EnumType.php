<?php

namespace TYPO3\CMS\Core\Database\Schema\Types;

use Doctrine\DBAL\Types\Types;

if (class_exists('TYPO3\CMS\Core\Database\Schema\Types\EnumType')) {
    return;
}

class EnumType extends Types
{
    public const TYPE = 'enum';
}

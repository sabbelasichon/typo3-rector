<?php

namespace TYPO3\CMS\Core\Schema;

use TYPO3\CMS\Core\Schema\Field\FieldTypeInterface;

if (class_exists('TYPO3\CMS\Core\Schema\TcaSchema')) {
    return;
}

class TcaSchema
{
    public function getField(string $fieldName): FieldTypeInterface
    {
    }
}

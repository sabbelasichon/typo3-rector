<?php

namespace TYPO3\CMS\Core\Schema;

if (class_exists('TYPO3\CMS\Core\Schema\TcaSchemaFactory')) {
    return;
}

class TcaSchemaFactory
{
    public function get(string $schemaName): TcaSchema
    {
    }
}

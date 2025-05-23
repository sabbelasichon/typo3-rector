<?php

namespace TYPO3\CMS\Core\Database\Driver;

use Doctrine\DBAL\Driver\PDO\Statement as DoctrineDbalPDOStatement;

if (class_exists('TYPO3\CMS\Core\Database\Driver\PDOStatement')) {
    return;
}

class PDOStatement extends DoctrineDbalPDOStatement
{
    /**
     * @return mixed
     */
    public function fetchColumn($columnIndex = 0)
    {
    }
}

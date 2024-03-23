<?php

namespace TYPO3\CMS\Core\Database;

use TYPO3\CMS\Core\Database\Query\Expression\ExpressionBuilder;

if (class_exists('TYPO3\CMS\Core\Database\Connection')) {
    return;
}

class Connection extends \Doctrine\DBAL\Connection
{
    /**
     * Represents a SQL NULL data type.
     */
    public const PARAM_NULL = \PDO::PARAM_NULL; // 0

    /**
     * Represents a SQL INTEGER data type.
     */
    public const PARAM_INT = \PDO::PARAM_INT; // 1

    /**
     * Represents a SQL CHAR, VARCHAR data type.
     */
    public const PARAM_STR = \PDO::PARAM_STR; // 2

    /**
     * Represents a SQL large object data type.
     */
    public const PARAM_LOB = \PDO::PARAM_LOB; // 3

    /**
     * Represents a recordset type. Not currently supported by any drivers.
     */
    public const PARAM_STMT = \PDO::PARAM_STMT; // 4

    /**
     * Represents a boolean data type.
     */
    public const PARAM_BOOL = \PDO::PARAM_BOOL; // 5

    /** @var ExpressionBuilder */
    protected $_expr;

    /**
     * @return ExpressionBuilder
     */
    public function getExpressionBuilder()
    {
        return $this->_expr;
    }

    public function lastInsertId()
    {}
}

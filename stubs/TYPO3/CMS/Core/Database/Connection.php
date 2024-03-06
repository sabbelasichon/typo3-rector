<?php

namespace TYPO3\CMS\Core\Database;

use TYPO3\CMS\Core\Database\Query\Expression\ExpressionBuilder;

if (class_exists('TYPO3\CMS\Core\Database\Connection')) {
    return;
}

class Connection extends \Doctrine\DBAL\Connection
{
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

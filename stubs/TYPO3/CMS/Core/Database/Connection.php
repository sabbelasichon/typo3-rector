<?php

namespace TYPO3\CMS\Core\Database;

use TYPO3\CMS\Core\Database\Query\Expression\ExpressionBuilder;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;

if (class_exists('TYPO3\CMS\Core\Database\Connection')) {
    return;
}

class Connection extends \Doctrine\DBAL\Connection
{
    const PARAM_STR = 'string';
    protected ExpressionBuilder $_expr;

    public function getExpressionBuilder(): ExpressionBuilder
    {
        return $this->_expr;
    }

    public function createQueryBuilder(): QueryBuilder
    {
    }

    public function lastInsertId()
    {

    }


}

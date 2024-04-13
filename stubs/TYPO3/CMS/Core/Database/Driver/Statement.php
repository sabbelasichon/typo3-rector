<?php

namespace Doctrine\DBAL\Driver\PDO;

use Doctrine\DBAL\Driver\PDOStatement;

if (class_exists('Doctrine\DBAL\Driver\PDO\Statement')) {
    return;
}

class Statement extends PDOStatement
{
}

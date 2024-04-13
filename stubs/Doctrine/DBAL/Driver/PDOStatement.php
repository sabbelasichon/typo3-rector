<?php

declare(strict_types=1);

namespace Doctrine\DBAL\Driver;

if (class_exists('Doctrine\DBAL\Driver\PDOStatement')) {
    return;
}

class PDOStatement extends \PDOStatement
{
}

<?php

namespace Doctrine\DBAL;

if (class_exists('Doctrine\DBAL\FetchMode')) {
    return;
}

class FetchMode
{
    /** @link PDO::FETCH_ASSOC */
    public const ASSOCIATIVE = 2;

    /** @link PDO::FETCH_NUM */
    public const NUMERIC = 3;

    /** @link PDO::FETCH_COLUMN */
    public const COLUMN = 7;
}

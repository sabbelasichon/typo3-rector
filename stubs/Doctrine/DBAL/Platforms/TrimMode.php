<?php
declare(strict_types=1);

namespace Doctrine\DBAL\Platforms;

if(enum_exists('Doctrine\DBAL\Platforms\TrimMode')) {
    return;
}

enum TrimMode: int
{
    case UNSPECIFIED = 0;
    case LEADING = 1;
    case TRAILING = 2;
    case BOTH = 3;
}

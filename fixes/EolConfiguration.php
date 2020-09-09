<?php
declare(strict_types=1);

namespace Symplify\PackageBuilder\Configuration;

final class EolConfiguration
{
    public static function getEolChar(): string
    {
        return "\n";
    }
}

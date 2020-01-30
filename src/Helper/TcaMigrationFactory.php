<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Helper;

use Ssch\TYPO3Rector\Migrations\Tca\CompositeTcaMigration;
use Ssch\TYPO3Rector\Migrations\Tca\TcaMigration;

final class TcaMigrationFactory
{
    public function create(): TcaMigration
    {
        return new CompositeTcaMigration([
            new \Ssch\TYPO3Rector\Migrations\Tca\v9\TcaMigration(),
            new \Ssch\TYPO3Rector\Migrations\Tca\v8\TcaMigration(),
            new \Ssch\TYPO3Rector\Migrations\Tca\v7\TcaMigration(),
        ]);
    }
}

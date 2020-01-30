<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Migrations\Tca;

interface TcaMigration
{
    public function migrate(array $tca);
}

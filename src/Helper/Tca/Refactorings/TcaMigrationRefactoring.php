<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Helper\Tca\Refactorings;

interface TcaMigrationRefactoring
{
    public function migrate(array $tca): array;

    public function getVersion(): string;
}

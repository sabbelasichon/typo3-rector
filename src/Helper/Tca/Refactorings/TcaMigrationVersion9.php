<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Helper\Tca\Refactorings;

final class TcaMigrationVersion9 implements TcaMigrationRefactoring
{
    use TcaMigrationRequire;

    /**
     * @var string
     */
    private const VERSION = '9.5';

    public function getVersion(): string
    {
        return self::VERSION;
    }
}

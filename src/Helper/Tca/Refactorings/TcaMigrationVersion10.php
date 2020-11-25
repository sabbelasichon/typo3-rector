<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Helper\Tca\Refactorings;

final class TcaMigrationVersion10 implements TcaMigrationRefactoring
{
    use TcaMigrationRequire;

    /**
     * @var string
     */
    private const VERSION = '10.4';

    public function getVersion(): string
    {
        return self::VERSION;
    }
}

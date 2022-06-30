<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Console\Application;

use Symfony\Component\Console\Application;

final class Typo3RectorConsoleApplication extends Application
{
    /**
     * @var string
     */
    private const NAME = 'TYPO3 Rector';

    public function __construct()
    {
        parent::__construct(self::NAME);
        $this->setDefaultCommand('typo3-generate');
    }
}

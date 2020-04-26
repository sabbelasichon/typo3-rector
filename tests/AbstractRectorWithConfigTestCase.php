<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests;

use Rector\Core\Testing\PHPUnit\AbstractRectorTestCase;

abstract class AbstractRectorWithConfigTestCase extends AbstractRectorTestCase
{
    protected function provideConfig(): string
    {
        return __DIR__ . '/config/typo3_rectors.yaml';
    }
}

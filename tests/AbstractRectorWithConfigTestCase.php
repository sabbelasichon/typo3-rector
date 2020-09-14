<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests;

use Rector\Core\Testing\PHPUnit\AbstractRectorTestCase;
use Symplify\SmartFileSystem\SmartFileInfo;

abstract class AbstractRectorWithConfigTestCase extends AbstractRectorTestCase
{
    protected function provideConfigFileInfo(): ?SmartFileInfo
    {
        return new SmartFileInfo(__DIR__ . '/config/typo3_rectors.php');
    }
}

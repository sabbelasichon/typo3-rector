<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Stubs;

use Nette\Loaders\RobotLoader;

final class StubLoader
{
    /**
     * @var bool
     */
    private $areStubsLoaded = false;

    /**
     * Load stubs after composer autoload is loaded + rector "process <src>" is loaded, so it is loaded only if the
     * classes are really missing.
     */
    public function loadStubs(): void
    {
        if ($this->areStubsLoaded) {
            return;
        }

        $stubDirectory = __DIR__ . '/../../stubs';

        $robotLoader = new RobotLoader();
        $robotLoader->acceptFiles = ['*.php', '*.stub'];
        $robotLoader->addDirectory($stubDirectory);
        $robotLoader->setTempDirectory(sys_get_temp_dir() . '/_typo3_rector_stubs');
        $robotLoader->register();

        $this->areStubsLoaded = true;
    }
}

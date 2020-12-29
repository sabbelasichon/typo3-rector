<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\DependencyInjection;

use Psr\Container\ContainerInterface;
use Rector\Core\Stubs\StubLoader;
use Ssch\TYPO3Rector\HttpKernel\Typo3RectorKernel;
use Ssch\TYPO3Rector\Stubs\StubLoader as Typo3StubsLoader;
use Symplify\PackageBuilder\Console\Input\StaticInputDetector;
use Symplify\SmartFileSystem\SmartFileInfo;

final class Typo3RectorContainerFactory
{
    /**
     * @param SmartFileInfo[] $configFileInfos
     * @api
     */
    public function createFromConfigs(array $configFileInfos): ContainerInterface
    {
        // to override the configs without clearing cache
        $environment = 'prod' . random_int(1, 10000000);
        $isDebug = StaticInputDetector::isDebug();

        $rectorKernel = new Typo3RectorKernel($environment, $isDebug);
        if ([] !== $configFileInfos) {
            $configFilePaths = $this->unpackRealPathsFromFileInfos($configFileInfos);
            $rectorKernel->setConfigs($configFilePaths);
        }

        $stubLoader = new StubLoader();
        $stubLoader->loadStubs();

        $typo3StubLoader = new Typo3StubsLoader();
        $typo3StubLoader->loadStubs();

        $rectorKernel->boot();

        return $rectorKernel->getContainer();
    }

    /**
     * @param SmartFileInfo[] $configFileInfos
     * @return string[]
     */
    private function unpackRealPathsFromFileInfos(array $configFileInfos): array
    {
        $configFilePaths = [];
        foreach ($configFileInfos as $configFileInfo) {
            // getRealPath() cannot be used, as it breaks in phar
            $configFilePaths[] = $configFileInfo->getRealPath() ?: $configFileInfo->getPathname();
        }

        return $configFilePaths;
    }
}

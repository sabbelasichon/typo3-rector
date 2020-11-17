<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\DependencyInjection;

use Psr\Container\ContainerInterface;
use Rector\Core\Stubs\StubLoader;
use Ssch\TYPO3Rector\HttpKernel\Typo3RectorKernel;
use Symplify\PackageBuilder\Console\Input\InputDetector;
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
        $isDebug = InputDetector::isDebug();

        $rectorKernel = new Typo3RectorKernel($environment, $isDebug);
        if ([] !== $configFileInfos) {
            $configFilePaths = $this->unpackRealPathsFromFileInfos($configFileInfos);
            $rectorKernel->setConfigs($configFilePaths);
        }

        $stubLoader = new StubLoader();
        $stubLoader->loadStubs();

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

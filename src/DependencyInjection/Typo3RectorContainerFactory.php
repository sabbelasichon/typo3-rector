<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\DependencyInjection;

use Psr\Container\ContainerInterface;
use Rector\Caching\Detector\ChangedFilesDetector;
use Rector\Core\Configuration\Configuration;
use Rector\Core\Stubs\StubLoader;
use Rector\Core\ValueObject\Bootstrap\BootstrapConfigs;
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
        $isDebug = StaticInputDetector::isDebug();

        $environment = $this->createEnvironment($configFileInfos);

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

    public function createFromBootstrapConfigs(BootstrapConfigs $bootstrapConfigs): ContainerInterface
    {
        $container = $this->createFromConfigs($bootstrapConfigs->getConfigFileInfos());

        $mainConfigFileInfo = $bootstrapConfigs->getMainConfigFileInfo();
        if (null !== $mainConfigFileInfo) {
            /** @var ChangedFilesDetector $changedFilesDetector */
            $changedFilesDetector = $container->get(ChangedFilesDetector::class);
            $changedFilesDetector->setFirstResolvedConfigFileInfo($mainConfigFileInfo);
        }

        /** @var Configuration $configuration */
        $configuration = $container->get(Configuration::class);
        $configuration->setBootstrapConfigs($bootstrapConfigs);

        return $container;
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

    /**
     * @see https://symfony.com/doc/current/components/dependency_injection/compilation.html#dumping-the-configuration-for-performance
     * @param SmartFileInfo[] $configFileInfos
     */
    private function createEnvironment(array $configFileInfos): string
    {
        $configHashes = [];
        foreach ($configFileInfos as $configFileInfo) {
            $configHashes[] = md5_file($configFileInfo->getRealPath());
        }

        $configHashString = implode('', $configHashes);
        return sha1($configHashString);
    }
}

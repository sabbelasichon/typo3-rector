<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Console\Application;

use Ssch\TYPO3Rector\Console\DependencyInjection\CompilerPasses\CommandsToApplicationCompilerPass;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\Kernel;

final class Typo3RectorKernel extends Kernel
{
    /**
     * @return iterable<mixed, BundleInterface>
     */
    public function registerBundles(): iterable
    {
        return [];
    }

    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $loader->load(__DIR__ . '/../../../config/application.php');
    }

    public function getCacheDir(): string
    {
        return sys_get_temp_dir() . '/_typo3_rector';
    }

    public function getLogDir(): string
    {
        return sys_get_temp_dir() . '/_typo3_rector_log';
    }

    protected function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new CommandsToApplicationCompilerPass());
    }
}

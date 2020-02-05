<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Console;

use Exception;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;
use Symplify\AutoBindParameter\DependencyInjection\CompilerPass\AutoBindParameterCompilerPass;
use Symplify\AutowireArrayParameter\DependencyInjection\CompilerPass\AutowireArrayParameterCompilerPass;
use Symplify\PackageBuilder\DependencyInjection\CompilerPass\AutoReturnFactoryCompilerPass;

final class ConsoleKernel extends Kernel
{
    /**
     * @inheritDoc
     */
    public function registerBundles()
    {
        return [];
    }

    /**
     * @inheritDoc
     *
     * @throws Exception
     */
    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        foreach([__DIR__ . '/../../vendor/rector/rector/config/config.yaml', __DIR__ . '/../../../../rector/rector/config/config.yaml'] as $file) {
            if(file_exists($file)) {
                $loader->load($file);
            }
        }

        $loader->load(__DIR__ . '/config/services.yml');
    }

    protected function build(ContainerBuilder $containerBuilder): void
    {
        $containerBuilder->addCompilerPass(new AutoReturnFactoryCompilerPass());
        $containerBuilder->addCompilerPass(new AutowireArrayParameterCompilerPass());
        // autowire Rectors by default (mainly for 3rd party code)
        $containerBuilder->addCompilerPass(new AutoBindParameterCompilerPass());
        $containerBuilder->addCompilerPass(new CommandsToApplicationCompilerPass());
    }
}

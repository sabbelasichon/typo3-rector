<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\HttpKernel;

use InvalidArgumentException;
use Rector\Core\Contract\Rector\RectorInterface;
use Rector\Core\DependencyInjection\Collector\ConfigureCallValuesCollector;
use Rector\Core\DependencyInjection\CompilerPass\MakeRectorsPublicCompilerPass;
use Rector\Core\DependencyInjection\CompilerPass\MergeImportedRectorConfigureCallValuesCompilerPass;
use Rector\Core\DependencyInjection\Loader\ConfigurableCallValuesCollectingPhpFileLoader;
use Symfony\Component\Config\Loader\DelegatingLoader;
use Symfony\Component\Config\Loader\GlobFileLoader;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Loader\LoaderResolver;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\Config\FileLocator;
use Symfony\Component\HttpKernel\Kernel;
use Symplify\AutowireArrayParameter\DependencyInjection\CompilerPass\AutowireArrayParameterCompilerPass;
use Symplify\ComposerJsonManipulator\Bundle\ComposerJsonManipulatorBundle;
use Symplify\ConsoleColorDiff\Bundle\ConsoleColorDiffBundle;
use Symplify\PackageBuilder\Contract\HttpKernel\ExtraConfigAwareKernelInterface;
use Symplify\PackageBuilder\DependencyInjection\CompilerPass\AutowireInterfacesCompilerPass;
use Symplify\PhpConfigPrinter\Bundle\PhpConfigPrinterBundle;
use Symplify\SimplePhpDocParser\Bundle\SimplePhpDocParserBundle;
use Symplify\Skipper\Bundle\SkipperBundle;

final class Typo3RectorKernel extends Kernel implements ExtraConfigAwareKernelInterface
{
    /**
     * @var string[]
     */
    private $configs = [];

    /**
     * @var ConfigureCallValuesCollector
     */
    private $configureCallValuesCollector;

    public function __construct(string $environment, bool $debug)
    {
        $this->configureCallValuesCollector = new ConfigureCallValuesCollector();

        parent::__construct($environment, $debug);
    }

    public function getCacheDir(): string
    {
        // manually configured, so it can be replaced in phar
        return sys_get_temp_dir() . '/typo3_rector';
    }

    public function getLogDir(): string
    {
        // manually configured, so it can be replaced in phar
        return sys_get_temp_dir() . '/typo3_rector_log';
    }

    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $possibleRectorConfigPaths = [
            __DIR__ . '/../../vendor/rector/rector/config/config.php',
            __DIR__ . '/../../../../rector/rector/config/config.php',
        ];

        foreach ($possibleRectorConfigPaths as $rectorConfig) {
            if (file_exists($rectorConfig)) {
                $loader->load($rectorConfig);
            }
        }

        $loader->load(__DIR__ . '/../../config/services.php');

        foreach ($this->configs as $config) {
            $loader->load($config);
        }
    }

    /**
     * @param string[] $configs
     */
    public function setConfigs(array $configs): void
    {
        $this->configs = $configs;
    }

    /**
     * @return BundleInterface[]
     */
    public function registerBundles(): array
    {
        return [
            new ConsoleColorDiffBundle(),
            new PhpConfigPrinterBundle(),
            new ComposerJsonManipulatorBundle(),
            new SkipperBundle(),
            new SimplePhpDocParserBundle(),
        ];
    }

    protected function build(ContainerBuilder $containerBuilder): void
    {
        $containerBuilder->addCompilerPass(new AutowireArrayParameterCompilerPass());

        // autowire Rectors by default (mainly for 3rd party code)
        $containerBuilder->addCompilerPass(new AutowireInterfacesCompilerPass([RectorInterface::class]));

        $containerBuilder->addCompilerPass(new MakeRectorsPublicCompilerPass());

        // add all merged arguments of Rector services
        $containerBuilder->addCompilerPass(
            new MergeImportedRectorConfigureCallValuesCompilerPass($this->configureCallValuesCollector)
        );
    }

    /**
     * This allows to use "%vendor%" variables in imports
     *
     * @param ContainerInterface|ContainerBuilder $container
     */
    protected function getContainerLoader(ContainerInterface $container): DelegatingLoader
    {
        $fileLocator = new FileLocator($this);

        if (! $container instanceof ContainerBuilder) {
            throw new InvalidArgumentException(sprintf('The container must be of type %s', ContainerBuilder::class));
        }

        $loaderResolver = new LoaderResolver([
            new GlobFileLoader($fileLocator),
            new ConfigurableCallValuesCollectingPhpFileLoader(
                $container,
                $fileLocator,
                $this->configureCallValuesCollector
            ),
        ]);

        return new DelegatingLoader($loaderResolver);
    }
}

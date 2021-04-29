<?php

declare(strict_types=1);

use Rector\Core\Bootstrap\RectorConfigsResolver;
use Rector\Core\Console\ConsoleApplication;
use Rector\Core\Console\Style\SymfonyStyleFactory;
use Rector\Core\DependencyInjection\RectorContainerFactory;
use Rector\Core\HttpKernel\RectorKernel;
use Rector\Core\ValueObject\Bootstrap\BootstrapConfigs;
use Symplify\PackageBuilder\Console\ShellCode;
use Symplify\PackageBuilder\Reflection\PrivatesCaller;
use Symplify\SmartFileSystem\SmartFileInfo;
use Tracy\Debugger;

// @ intentionally: continue anyway
@ini_set('memory_limit', '-1');

// Performance boost
error_reporting(E_ALL);
ini_set('display_errors', 'stderr');
gc_disable();

define('__RECTOR_RUNNING__', true);

// Require Composer autoload.php
$autoloadIncluder = new AutoloadIncluder();
$autoloadIncluder->includeDependencyOrRepositoryVendorAutoloadIfExists();

$autoloadIncluder->loadIfExistsAndNotLoadedYet(__DIR__ . '/../vendor/scoper-autoload.php');

$autoloadIncluder->autoloadProjectAutoloaderFile();
$autoloadIncluder->autoloadFromCommandLine();

$symfonyStyleFactory = new SymfonyStyleFactory(new PrivatesCaller());
$symfonyStyle = $symfonyStyleFactory->create();

$rectorConfigsResolver = new RectorConfigsResolver();

// for simpler debugging output
if (class_exists(Debugger::class)) {
    Debugger::$maxDepth = 2;
}

try {
    $bootstrapConfigs = $rectorConfigsResolver->provide();
    $configFileInfos = $bootstrapConfigs->getConfigFileInfos();
    $configFileInfos[] = new SmartFileInfo(__DIR__.'/../config/config.php');

    $bootstrapConfigs = new BootstrapConfigs($bootstrapConfigs->getMainConfigFileInfo(), $configFileInfos);
    $rectorContainerFactory = new RectorContainerFactory();
    $container = $rectorContainerFactory->createFromBootstrapConfigs($bootstrapConfigs);
} catch (Throwable $throwable) {
    $symfonyStyle->error($throwable->getMessage());
    exit(ShellCode::ERROR);
}


// preload local InstalledVersions.php - to fix incorrect version by same-named class in phpstan
$currentlyInstalledVersions = __DIR__ . '/../../../../vendor/composer/InstalledVersions.php';
if (file_exists($currentlyInstalledVersions)) {
    require_once $currentlyInstalledVersions;
}


/** @var ConsoleApplication $application */
$application = $container->get(ConsoleApplication::class);
exit($application->run());

final class AutoloadIncluder
{
    /**
     * @var string[]
     */
    private $alreadyLoadedAutoloadFiles = [];

    public function includeDependencyOrRepositoryVendorAutoloadIfExists(): void
    {
        // Rector's vendor is already loaded
        if (class_exists(RectorKernel::class)) {
            return;
        }

        // in Rector develop repository
        $this->loadIfExistsAndNotLoadedYet(__DIR__ . '/../vendor/autoload.php');
    }

    /**
     * In case Rector is installed as vendor dependency,
     * this autoloads the project vendor/autoload.php, including Rector
     */
    public function autoloadProjectAutoloaderFile(): void
    {
        $this->loadIfExistsAndNotLoadedYet(__DIR__ . '/../../../autoload.php');
    }

    public function autoloadFromCommandLine(): void
    {
        $cliArgs = $_SERVER['argv'];

        $autoloadOptionPosition = array_search('-a', $cliArgs, true) ?: array_search('--autoload-file', $cliArgs, true);
        if (! $autoloadOptionPosition) {
            return;
        }

        $autoloadFileValuePosition = $autoloadOptionPosition + 1;
        $fileToAutoload = $cliArgs[$autoloadFileValuePosition] ?? null;
        if ($fileToAutoload === null) {
            return;
        }

        $this->loadIfExistsAndNotLoadedYet($fileToAutoload);
    }

    public function loadIfExistsAndNotLoadedYet(string $filePath): void
    {
        if (! file_exists($filePath)) {
            return;
        }

        if (in_array($filePath, $this->alreadyLoadedAutoloadFiles, true)) {
            return;
        }

        $this->alreadyLoadedAutoloadFiles[] = realpath($filePath);

        require_once $filePath;
    }
}

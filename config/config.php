<?php

declare(strict_types=1);

use League\Flysystem\Filesystem;
use League\Flysystem\InMemory\InMemoryFilesystemAdapter;
use League\Flysystem\Local\LocalFilesystemAdapter;
use Rector\Config\RectorConfig;
use Rector\Testing\PHPUnit\StaticPHPUnitEnvironment;
use Ssch\TYPO3Rector\Configuration\Typo3Option;
use Ssch\TYPO3Rector\Contract\FilesystemInterface;
use Ssch\TYPO3Rector\Filesystem\FileInfoFactory;
use Ssch\TYPO3Rector\Filesystem\FilesFinder;
use Ssch\TYPO3Rector\Filesystem\FlysystemFilesystem;
use Ssch\TYPO3Rector\NodeAnalyzer\ExtbaseControllerRedirectAnalyzer;
use Ssch\TYPO3Rector\NodeFactory\InjectMethodFactory;
use Ssch\TYPO3Rector\NodeFactory\Typo3GlobalsFactory;
use Ssch\TYPO3Rector\NodeResolver\Typo3NodeResolver;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->importNames();
    $rectorConfig->phpstanConfig(Typo3Option::PHPSTAN_FOR_RECTOR_PATH);
    // this will not import root namespace classes, like \DateTime or \Exception
    $rectorConfig->importShortClasses(false);

    $rectorConfig->singleton(FileInfoFactory::class);
    $rectorConfig->singleton(FilesFinder::class);
    $rectorConfig->singleton(ExtbaseControllerRedirectAnalyzer::class);
    $rectorConfig->singleton(InjectMethodFactory::class);
    $rectorConfig->singleton(Typo3GlobalsFactory::class);
    $rectorConfig->singleton(Typo3NodeResolver::class);
    $rectorConfig->bind(FilesystemInterface::class, static function () {
        $argv = $_SERVER['argv'] ?? [];
        $isDryRun = in_array('--dry-run', $argv) || in_array('-n', $argv);
        if (StaticPHPUnitEnvironment::isPHPUnitRun() || $isDryRun) {
            $adapter = new InMemoryFilesystemAdapter();
        } else {
            $adapter = new LocalFilesystemAdapter('/');
        }
        return new FlysystemFilesystem(new Filesystem($adapter));
    });
};

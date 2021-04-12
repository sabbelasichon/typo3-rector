<?php

declare(strict_types=1);

use Ssch\TYPO3Rector\Rector\Composer\ReplacePackageComposerRector;
use Ssch\TYPO3Rector\ValueObject\ReplacePackage;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\SymfonyPhpConfig\ValueObjectInliner;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/../../../../../config/config.php');
    $services = $containerConfigurator->services();

    $composerExtensions = [
        new ReplacePackage('typo3-ter/news', 'georgringer/news'),
        new ReplacePackage('typo3-ter/filefill', 'ichhabrecht/filefill'),
    ];

    $services->set(ReplacePackageComposerRector::class)
        ->call('configure', [
            [
                ReplacePackageComposerRector::REPLACE_PACKAGES => ValueObjectInliner::inline($composerExtensions),
            ],
        ]);
};

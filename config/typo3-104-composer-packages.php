<?php

declare(strict_types=1);

use Rector\Composer\Rector\ChangePackageVersionComposerRector;
use Rector\Composer\ValueObject\PackageAndVersion;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\SymfonyPhpConfig\ValueObjectInliner;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/services.php');
    $services = $containerConfigurator->services();
    $services->set(ChangePackageVersionComposerRector::class)
        ->call('configure', [
            [
                ChangePackageVersionComposerRector::PACKAGES_AND_VERSIONS => ValueObjectInliner::inline([
                    new PackageAndVersion('typo3/cms-about', '^10.4'),
                    new PackageAndVersion('typo3/cms-adminpanel', '^10.4'),
                    new PackageAndVersion('typo3/cms-backend', '^10.4'),
                    new PackageAndVersion('typo3/cms-belog', '^10.4'),
                    new PackageAndVersion('typo3/cms-beuser', '^10.4'),
                    new PackageAndVersion('typo3/cms-core', '^10.4'),
                    new PackageAndVersion('typo3/cms-dashboard', '^10.4'),
                    new PackageAndVersion('typo3/cms-extbase', '^10.4'),
                    new PackageAndVersion('typo3/cms-extensionmanager', '^10.4'),
                    new PackageAndVersion('typo3/cms-felogin', '^10.4'),
                    new PackageAndVersion('typo3/cms-filelist', '^10.4'),
                    new PackageAndVersion('typo3/cms-filemetadata', '^10.4'),
                    new PackageAndVersion('typo3/cms-fluid', '^10.4'),
                    new PackageAndVersion('typo3/cms-fluid-styled-content', '^10.4'),
                    new PackageAndVersion('typo3/cms-form', '^10.4'),
                    new PackageAndVersion('typo3/cms-frontend', '^10.4'),
                    new PackageAndVersion('typo3/cms-impexp', '^10.4'),
                    new PackageAndVersion('typo3/cms-indexed-search', '^10.4'),
                    new PackageAndVersion('typo3/cms-info', '^10.4'),
                    new PackageAndVersion('typo3/cms-install', '^10.4'),
                    new PackageAndVersion('typo3/cms-linkvalidator', '^10.4'),
                    new PackageAndVersion('typo3/cms-lowlevel', '^10.4'),
                    new PackageAndVersion('typo3/cms-opendocs', '^10.4'),
                    new PackageAndVersion('typo3/cms-recordlist', '^10.4'),
                    new PackageAndVersion('typo3/cms-recycler', '^10.4'),
                    new PackageAndVersion('typo3/cms-redirects', '^10.4'),
                    new PackageAndVersion('typo3/cms-reports', '^10.4'),
                    new PackageAndVersion('typo3/cms-rte-ckeditor', '^10.4'),
                    new PackageAndVersion('typo3/cms-scheduler', '^10.4'),
                    new PackageAndVersion('typo3/cms-seo', '^10.4'),
                    new PackageAndVersion('typo3/cms-setup', '^10.4'),
                    new PackageAndVersion('typo3/cms-sys-note', '^10.4'),
                    new PackageAndVersion('typo3/cms-t3editor', '^10.4'),
                    new PackageAndVersion('typo3/cms-tstemplate', '^10.4'),
                    new PackageAndVersion('typo3/cms-viewpage', '^10.4'),
                    new PackageAndVersion('typo3/cms-workspaces', '^10.4'),
                ]),
            ],
        ]);
};

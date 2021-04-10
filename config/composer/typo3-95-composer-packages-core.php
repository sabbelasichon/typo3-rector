<?php

declare(strict_types=1);

use Rector\Composer\Rector\ChangePackageVersionComposerRector;
use Rector\Composer\Rector\RemovePackageComposerRector;
use Rector\Composer\ValueObject\PackageAndVersion;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\SymfonyPhpConfig\ValueObjectInliner;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/../config.php');
    $services = $containerConfigurator->services();
    $services->set('remove_typo3_cms_composer_package_version_95')
        ->class(RemovePackageComposerRector::class)
        ->call(
        'configure',
        [[
            RemovePackageComposerRector::PACKAGE_NAMES => [
                'typo3/cms',
                'typo3/cms-context-help',
                'typo3/cms-info-pagetsconfig',
                'typo3/cms-wizard-crpages',
                'typo3/cms-wizard-sortpages',
                'typo3/cms-cshmanual',
                'typo3/cms-func',
                'typo3/cms-documentation',
                'dmitryd/typo3-realurl',
                'typo3-ter/typo3-realurl',
            ],
        ]]
    );
    $services->set('change_composer_json_version_95')
        ->class(ChangePackageVersionComposerRector::class)
        ->call(
        'configure',
        [[
            ChangePackageVersionComposerRector::PACKAGES_AND_VERSIONS => ValueObjectInliner::inline([
                new PackageAndVersion('typo3/cms-about', '^9.5'),
                new PackageAndVersion('typo3/cms-adminpanel', '^9.5'),
                new PackageAndVersion('typo3/cms-backend', '^9.5'),
                new PackageAndVersion('typo3/cms-belog', '^9.5'),
                new PackageAndVersion('typo3/cms-beuser', '^9.5'),
                new PackageAndVersion('typo3/cms-core', '^9.5'),
                new PackageAndVersion('typo3/cms-extbase', '^9.5'),
                new PackageAndVersion('typo3/cms-extensionmanager', '^9.5'),
                new PackageAndVersion('typo3/cms-feedit', '^9.5'),
                new PackageAndVersion('typo3/cms-felogin', '^9.5'),
                new PackageAndVersion('typo3/cms-filelist', '^9.5'),
                new PackageAndVersion('typo3/cms-filemetadata', '^9.5'),
                new PackageAndVersion('typo3/cms-fluid', '^9.5'),
                new PackageAndVersion('typo3/cms-fluid-styled-content', '^9.5'),
                new PackageAndVersion('typo3/cms-form', '^9.5'),
                new PackageAndVersion('typo3/cms-frontend', '^9.5'),
                new PackageAndVersion('typo3/cms-impexp', '^9.5'),
                new PackageAndVersion('typo3/cms-indexed-search', '^9.5'),
                new PackageAndVersion('typo3/cms-info', '^9.5'),
                new PackageAndVersion('typo3/cms-install', '^9.5'),
                new PackageAndVersion('typo3/cms-linkvalidator', '^9.5'),
                new PackageAndVersion('typo3/cms-lowlevel', '^9.5'),
                new PackageAndVersion('typo3/cms-opendocs', '^9.5'),
                new PackageAndVersion('typo3/cms-recordlist', '^9.5'),
                new PackageAndVersion('typo3/cms-recycler', '^9.5'),
                new PackageAndVersion('typo3/cms-redirects', '^9.5'),
                new PackageAndVersion('typo3/cms-reports', '^9.5'),
                new PackageAndVersion('typo3/cms-rsaauth', '^9.5'),
                new PackageAndVersion('typo3/cms-rte-ckeditor', '^9.5'),
                new PackageAndVersion('typo3/cms-scheduler', '^9.5'),
                new PackageAndVersion('typo3/cms-seo', '^9.5'),
                new PackageAndVersion('typo3/cms-setup', '^9.5'),
                new PackageAndVersion('typo3/cms-sys-action', '^9.5'),
                new PackageAndVersion('typo3/cms-sys-note', '^9.5'),
                new PackageAndVersion('typo3/cms-t3editor', '^9.5'),
                new PackageAndVersion('typo3/cms-taskcenter', '^9.5'),
                new PackageAndVersion('typo3/cms-tstemplate', '^9.5'),
                new PackageAndVersion('typo3/cms-viewpage', '^9.5'),
                new PackageAndVersion('typo3/cms-workspaces', '^9.5'),
                new PackageAndVersion('helhum/typo3-console', '^5.0'),
                new PackageAndVersion('helhum/dotenv-connector', '^3.0'),
                new PackageAndVersion('helhum/typo3-secure-web', '^0.3.0'),
                new PackageAndVersion('typo3-console/composer-typo3-auto-install', '^0.3.0'),
            ]),
        ]]
    );
};

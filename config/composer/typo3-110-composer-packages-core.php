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
    $services->set('remove_typo3_cms_composer_package_version_110')
        ->class(RemovePackageComposerRector::class)
        ->call(
                 'configure',
                 [[
                     RemovePackageComposerRector::PACKAGE_NAMES => [
                         'typo3/cms-context-help',
                         'typo3/cms-info-pagetsconfig',
                         'typo3/cms-wizard-crpages',
                         'typo3/cms-rsaauth',
                     ],
                 ]]
             );
    $services->set('change_composer_json_version_110')
        ->class(ChangePackageVersionComposerRector::class)
        ->call('configure', [[
            ChangePackageVersionComposerRector::PACKAGES_AND_VERSIONS => ValueObjectInliner::inline([
                new PackageAndVersion('typo3/cms-about', '^11.0'),
                new PackageAndVersion('typo3/cms-adminpanel', '^11.0'),
                new PackageAndVersion('typo3/cms-backend', '^11.0'),
                new PackageAndVersion('typo3/cms-belog', '^11.0'),
                new PackageAndVersion('typo3/cms-beuser', '^11.0'),
                new PackageAndVersion('typo3/cms-core', '^11.0'),
                new PackageAndVersion('typo3/cms-dashboard', '^11.0'),
                new PackageAndVersion('typo3/cms-extbase', '^11.0'),
                new PackageAndVersion('typo3/cms-extensionmanager', '^11.0'),
                new PackageAndVersion('typo3/cms-felogin', '^11.0'),
                new PackageAndVersion('typo3/cms-filelist', '^11.0'),
                new PackageAndVersion('typo3/cms-filemetadata', '^11.0'),
                new PackageAndVersion('typo3/cms-fluid', '^11.0'),
                new PackageAndVersion('typo3/cms-fluid-styled-content', '^11.0'),
                new PackageAndVersion('typo3/cms-form', '^11.0'),
                new PackageAndVersion('typo3/cms-frontend', '^11.0'),
                new PackageAndVersion('typo3/cms-impexp', '^11.0'),
                new PackageAndVersion('typo3/cms-indexed-search', '^11.0'),
                new PackageAndVersion('typo3/cms-info', '^11.0'),
                new PackageAndVersion('typo3/cms-install', '^11.0'),
                new PackageAndVersion('typo3/cms-linkvalidator', '^11.0'),
                new PackageAndVersion('typo3/cms-lowlevel', '^11.0'),
                new PackageAndVersion('typo3/cms-opendocs', '^11.0'),
                new PackageAndVersion('typo3/cms-recordlist', '^11.0'),
                new PackageAndVersion('typo3/cms-recycler', '^11.0'),
                new PackageAndVersion('typo3/cms-redirects', '^11.0'),
                new PackageAndVersion('typo3/cms-reports', '^11.0'),
                new PackageAndVersion('typo3/cms-rte-ckeditor', '^11.0'),
                new PackageAndVersion('typo3/cms-scheduler', '^11.0'),
                new PackageAndVersion('typo3/cms-seo', '^11.0'),
                new PackageAndVersion('typo3/cms-setup', '^11.0'),
                new PackageAndVersion('typo3/cms-sys-note', '^11.0'),
                new PackageAndVersion('typo3/cms-t3editor', '^11.0'),
                new PackageAndVersion('typo3/cms-tstemplate', '^11.0'),
                new PackageAndVersion('typo3/cms-viewpage', '^11.0'),
                new PackageAndVersion('typo3/cms-workspaces', '^11.0'),
            ]),
        ]]);
};

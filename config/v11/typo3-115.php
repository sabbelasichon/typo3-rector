<?php

declare(strict_types=1);

use Rector\Renaming\Rector\StaticCall\RenameStaticMethodRector;
use Rector\Renaming\ValueObject\RenameStaticMethod;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\SymfonyPhpConfig\ValueObjectInliner;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/../config.php');
    $services = $containerConfigurator->services();
    $services->set('rename_static_method_general_utility_is_abs_path_to_path_utility_is_absolute_path')
        ->class(RenameStaticMethodRector::class)
        ->call('configure', [[
            RenameStaticMethodRector::OLD_TO_NEW_METHODS_BY_CLASSES => ValueObjectInliner::inline([
                new RenameStaticMethod(
                    'TYPO3\CMS\Core\Utility\GeneralUtility',
                    'isAbsPath',
                    'TYPO3\CMS\Core\Utility\PathUtility',
                    'isAbsolutePath'
                ),
            ]),
        ]]);
};

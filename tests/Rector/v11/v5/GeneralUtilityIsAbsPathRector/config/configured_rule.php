<?php

declare(strict_types=1);

use Rector\Renaming\Rector\StaticCall\RenameStaticMethodRector;
use Rector\Renaming\ValueObject\RenameStaticMethod;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/../../../../../../config/config_test.php');
    $services = $containerConfigurator->services();

    $services->set(RenameStaticMethodRector::class)
        ->configure([
            new RenameStaticMethod(
                'TYPO3\CMS\Core\Utility\GeneralUtility',
                'isAbsPath',
                'TYPO3\CMS\Core\Utility\PathUtility',
                'isAbsolutePath'
            ),
        ]);
};

<?php

declare(strict_types=1);

use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Ssch\TYPO3Rector\Rector\Backend\Domain\Repository\Localization\RemoveColPosParameterRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/services.php');

    $services = $containerConfigurator->services();

    $services->set(RemoveColPosParameterRector::class);

    $services->set(RenameMethodRector::class)
        ->call('configure', [[
            RenameMethodRector::OLD_TO_NEW_METHODS_BY_CLASS => [
                'TYPO3\CMS\Backend\Controller\Page\LocalizationController' => [
                    'getUsedLanguagesInPageAndColumn' => 'getUsedLanguagesInPage'
                ]
            ],
        ]]);
};

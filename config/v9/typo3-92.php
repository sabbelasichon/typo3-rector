<?php

declare(strict_types=1);

use Rector\Renaming\Rector\Name\RenameClassRector;
use Ssch\TYPO3Rector\Rector\v9\v2\GeneralUtilityGetUrlRequestHeadersRector;
use Ssch\TYPO3Rector\Rector\v9\v2\PageNotFoundAndErrorHandlingRector;
use Ssch\TYPO3Rector\Rector\v9\v2\RenameMethodCallToEnvironmentMethodCallRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/../config.php');
    $services = $containerConfigurator->services();
    $services->set(RenameMethodCallToEnvironmentMethodCallRector::class);
    $services->set(RenameClassRector::class)
        ->configure([
            'TYPO3\CMS\Core\Cache\Frontend\StringFrontend' => 'TYPO3\CMS\Core\Cache\Frontend\VariableFrontend',
        ]);
    $services->set(GeneralUtilityGetUrlRequestHeadersRector::class);
    $services->set(PageNotFoundAndErrorHandlingRector::class);
};

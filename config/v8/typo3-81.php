<?php

declare(strict_types=1);

use Ssch\TYPO3Rector\Rector\v8\v1\Array2XmlCsToArray2XmlRector;
use Ssch\TYPO3Rector\Rector\v8\v1\RefactorDbConstantsRector;
use Ssch\TYPO3Rector\Rector\v8\v1\TypoScriptFrontendControllerCharsetConverterRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/../config.php');
    $services = $containerConfigurator->services();
    $services->set(RefactorDbConstantsRector::class);
    $services->set(Array2XmlCsToArray2XmlRector::class);
    $services->set(TypoScriptFrontendControllerCharsetConverterRector::class);
};

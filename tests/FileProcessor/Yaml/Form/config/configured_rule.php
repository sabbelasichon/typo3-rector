<?php

declare(strict_types=1);

use Ssch\TYPO3Rector\FileProcessor\Yaml\Form\Rector\EmailFinisherRector;
use Ssch\TYPO3Rector\FileProcessor\Yaml\Form\Rector\TranslationFileRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/../../../../../config/config_test.php');

    $services = $containerConfigurator->services();
    $services->set(EmailFinisherRector::class);
    $services->set(TranslationFileRector::class);
};

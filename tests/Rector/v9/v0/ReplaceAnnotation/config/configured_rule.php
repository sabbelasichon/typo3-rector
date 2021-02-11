<?php

declare(strict_types=1);

use Ssch\TYPO3Rector\Rector\v9\v0\ReplaceAnnotationRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/../../../../../../config/services.php');
    $services = $containerConfigurator->services();

    $services->set(ReplaceAnnotationRector::class)
        ->call('configure', [[
            ReplaceAnnotationRector::OLD_TO_NEW_ANNOTATIONS => [
                'lazy' => 'TYPO3\CMS\Extbase\Annotation\ORM\Lazy',
                'cascade' => 'TYPO3\CMS\Extbase\Annotation\ORM\Cascade("remove")',
                'transient' => 'TYPO3\CMS\Extbase\Annotation\ORM\Transient',
            ],
        ]]);
};

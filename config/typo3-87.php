<?php

declare(strict_types=1);

use Rector\Renaming\Rector\Class_\RenameClassRector;
use Ssch\TYPO3Rector\Rector\Backend\Utility\BackendUtilityGetRecordRawRector;
use Ssch\TYPO3Rector\Rector\Core\DataHandling\DataHandlerRmCommaRector;
use Ssch\TYPO3Rector\Rector\Core\TypoScript\TemplateServiceSplitConfArrayRector;
use Ssch\TYPO3Rector\Rector\Extbase\ChangeAttemptsParameterConsoleOutputRector;
use Ssch\TYPO3Rector\Rector\Frontend\ContentObject\RefactorRemovedMarkerMethodsFromContentObjectRendererRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/services.php');

    $services = $containerConfigurator->services();

    $services->set(BackendUtilityGetRecordRawRector::class);

    $services->set(DataHandlerRmCommaRector::class);

    $services->set(TemplateServiceSplitConfArrayRector::class);

    $services->set(RefactorRemovedMarkerMethodsFromContentObjectRendererRector::class);

    $services->set(ChangeAttemptsParameterConsoleOutputRector::class);

    $services->set(RenameClassRector::class)
        ->call('configure', [[
            RenameClassRector::OLD_TO_NEW_CLASSES => [
                ['TYPO3\CMS\Extbase\Service\TypoScriptService' => 'TYPO3\CMS\Core\TypoScript\TypoScriptService']
            ],
        ]]);
};

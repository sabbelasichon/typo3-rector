<?php

declare(strict_types=1);

use Ssch\TYPO3Rector\Rector\v10\v0\RemoveSeliconFieldPathRector;
use Ssch\TYPO3Rector\Rector\v10\v0\RemoveTcaOptionSetToDefaultOnCopyRector;
use Ssch\TYPO3Rector\Rector\v10\v3\RemoveExcludeOnTransOrigPointerFieldRector;
use Ssch\TYPO3Rector\Rector\v10\v3\RemoveShowRecordFieldListInsideInterfaceSectionRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/../services.php');
    $services = $containerConfigurator->services();
    $services->set(RemoveSeliconFieldPathRector::class);
    $services->set(RemoveTcaOptionSetToDefaultOnCopyRector::class);
    $services->set(RemoveExcludeOnTransOrigPointerFieldRector::class);
    $services->set(RemoveShowRecordFieldListInsideInterfaceSectionRector::class);
};

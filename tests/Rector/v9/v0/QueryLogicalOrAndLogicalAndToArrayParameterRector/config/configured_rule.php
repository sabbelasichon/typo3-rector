<?php

declare(strict_types=1);

use Rector\Php71\Rector\FuncCall\RemoveExtraParametersRector;
use Ssch\TYPO3Rector\Rector\v9\v0\QueryLogicalOrAndLogicalAndToArrayParameterRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/../../../../../../config/config_test.php');

    $services = $containerConfigurator->services();
    $services->set(QueryLogicalOrAndLogicalAndToArrayParameterRector::class);
    $services->set(RemoveExtraParametersRector::class);
};

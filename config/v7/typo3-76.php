<?php

declare(strict_types=1);

use function Rector\SymfonyPhpConfig\inline_value_objects;
use Rector\Transform\Rector\MethodCall\MethodCallToStaticCallRector;
use Rector\Transform\ValueObject\MethodCallToStaticCall;
use Ssch\TYPO3Rector\Rector\v7\v6\RenamePiListBrowserResultsRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use TYPO3\CMS\Backend\Template\DocumentTemplate;
use TYPO3\CMS\Backend\Utility\BackendUtility;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/../services.php');

    $services = $containerConfigurator->services();

    $services->set(RenamePiListBrowserResultsRector::class);

    $services->set(MethodCallToStaticCallRector::class)
        ->call('configure', [[
            MethodCallToStaticCallRector::METHOD_CALLS_TO_STATIC_CALLS => inline_value_objects([
                new MethodCallToStaticCall(
                   DocumentTemplate::class,
                   'issueCommand',
                   BackendUtility::class,
                   'getLinkToDataHandlerAction'
                    ),
            ]),
        ]]);
};

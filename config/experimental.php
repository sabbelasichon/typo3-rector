<?php

declare(strict_types=1);

use Rector\Transform\Rector\MethodCall\MethodCallToStaticCallRector;
use Rector\Transform\ValueObject\MethodCallToStaticCall;
use Ssch\TYPO3Rector\Rector\Experimental\OptionalConstructorToHardRequirementRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\SymfonyPhpConfig\ValueObjectInliner;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set('typo3_objectmanager_get_to_generalutility_makeinstance')
        ->class(MethodCallToStaticCallRector::class)
        ->call('configure', [
            [
                MethodCallToStaticCallRector::METHOD_CALLS_TO_STATIC_CALLS => ValueObjectInliner::inline([
                    new MethodCallToStaticCall(
                        'TYPO3\CMS\Extbase\Object\ObjectManagerInterface',
                        'get',
                        'TYPO3\CMS\Core\Utility\GeneralUtility',
                        'makeInstance'
                    ),
                ]),
            ],
        ]);
    $services->set(OptionalConstructorToHardRequirementRector::class);
};

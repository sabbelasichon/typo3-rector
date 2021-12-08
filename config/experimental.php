<?php

declare(strict_types=1);

use Rector\Transform\Rector\MethodCall\MethodCallToStaticCallRector;
use Rector\Transform\ValueObject\MethodCallToStaticCall;
use Ssch\TYPO3Rector\Rector\Experimental\OptionalConstructorToHardRequirementRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(MethodCallToStaticCallRector::class)
        ->configure([
            new MethodCallToStaticCall(
                'TYPO3\CMS\Extbase\Object\ObjectManagerInterface',
                'get',
                'TYPO3\CMS\Core\Utility\GeneralUtility',
                'makeInstance'
            ),
        ]);
    $services->set(OptionalConstructorToHardRequirementRector::class);
};

<?php

declare(strict_types=1);

use Rector\Transform\Rector\MethodCall\MethodCallToStaticCallRector;
use Rector\Transform\ValueObject\MethodCallToStaticCall;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/../../../../../../config/config_test.php');

    $services = $containerConfigurator->services();

    $services->set(MethodCallToStaticCallRector::class)
        ->configure([
            new MethodCallToStaticCall(ObjectManagerInterface::class, 'get', GeneralUtility::class, 'makeInstance'),
            new MethodCallToStaticCall(ObjectManager::class, 'get', GeneralUtility::class, 'makeInstance'),
        ]);
};

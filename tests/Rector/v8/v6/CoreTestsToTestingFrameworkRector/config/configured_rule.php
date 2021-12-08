<?php

declare(strict_types=1);

use Rector\Renaming\Rector\Name\RenameClassRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use TYPO3\CMS\Core\Tests\FunctionalTestCase as CoreFunctionalTestCase;
use TYPO3\CMS\Core\Tests\UnitTestCase as CoreUnitTestCase;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/../../../../../../config/config_test.php');

    $services = $containerConfigurator->services();
    $services->set(RenameClassRector::class)
        ->configure([
            CoreUnitTestCase::class => UnitTestCase::class,
            CoreFunctionalTestCase::class => FunctionalTestCase::class,
        ]);
};

<?php

declare(strict_types=1);

use Nimut\TestingFramework\Exception\Exception as NimutException;
use Nimut\TestingFramework\MockObject\AccessibleMockObjectInterface as NimutAccessibleMockObjectInterface;
use Nimut\TestingFramework\TestCase\FunctionalTestCase as NimutFunctionalTestCase;
use Nimut\TestingFramework\TestCase\UnitTestCase as NimutUnitTestCase;
use Nimut\TestingFramework\TestCase\ViewHelperBaseTestcase as NimutViewHelperBaseTestcase;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use TYPO3\TestingFramework\Core\AccessibleObjectInterface;
use TYPO3\TestingFramework\Core\Exception as TestingFrameworkException;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
use TYPO3\TestingFramework\Fluid\Unit\ViewHelpers\ViewHelperBaseTestcase;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/services.php');

    $services = $containerConfigurator->services();
    $services->set(RenameClassRector::class)
        ->call('configure', [[
            RenameClassRector::OLD_TO_NEW_CLASSES => [
                NimutUnitTestCase::class => UnitTestCase::class,
                NimutFunctionalTestCase::class => FunctionalTestCase::class,
                NimutViewHelperBaseTestcase::class => ViewHelperBaseTestcase::class,
                NimutAccessibleMockObjectInterface::class => AccessibleObjectInterface::class,
                NimutException::class => TestingFrameworkException::class,
            ],
        ]]);
};

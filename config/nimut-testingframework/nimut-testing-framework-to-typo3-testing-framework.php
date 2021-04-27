<?php

declare(strict_types=1);

use Rector\Renaming\Rector\Name\RenameClassRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/../config.php');

    $services = $containerConfigurator->services();
    $services->set('nimut_testing_framework_to_typo3_testing_framework')
        ->class(RenameClassRector::class)
        ->call('configure', [[
            RenameClassRector::OLD_TO_NEW_CLASSES => [
                'Nimut\TestingFramework\TestCase\UnitTestCase' => 'TYPO3\TestingFramework\Core\Unit\UnitTestCase',
                'Nimut\TestingFramework\TestCase\FunctionalTestCase' => 'TYPO3\TestingFramework\Core\Functional\FunctionalTestCase',
                'Nimut\TestingFramework\TestCase\ViewHelperBaseTestcase' => 'TYPO3\TestingFramework\Fluid\Unit\ViewHelpers\ViewHelperBaseTestcase',
                'Nimut\TestingFramework\MockObject\AccessibleMockObjectInterface' => 'TYPO3\TestingFramework\Core\AccessibleObjectInterface',
                'Nimut\TestingFramework\Exception\Exception' => 'TYPO3\TestingFramework\Core\Exception',
            ],
        ]]);
};

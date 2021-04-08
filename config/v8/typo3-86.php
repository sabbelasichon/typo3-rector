<?php

declare(strict_types=1);

use Rector\Renaming\Rector\Name\RenameClassRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use TYPO3\CMS\Core\Tests\FunctionalTestCase;
use TYPO3\CMS\Core\Tests\UnitTestCase;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/../services.php');
    $services = $containerConfigurator->services();
    $services->set('namespace_typo3_cms_core_tests_to__typo3_testing_framework_core')
        ->class(RenameClassRector::class)
        ->call(
        'configure',
        [[
            RenameClassRector::OLD_TO_NEW_CLASSES => [
                UnitTestCase::class => \TYPO3\TestingFramework\Core\Unit\UnitTestCase::class,
                FunctionalTestCase::class => \TYPO3\TestingFramework\Core\Functional\FunctionalTestCase::class,
            ],
        ]]
    );
};

<?php

declare(strict_types=1);

use Rector\Renaming\Rector\Name\RenameClassRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/../config.php');
    $services = $containerConfigurator->services();
    $services->set('namespace_typo3_cms_core_tests_to__typo3_testing_framework_core')
        ->class(RenameClassRector::class)
        ->call(
        'configure',
        [[
            RenameClassRector::OLD_TO_NEW_CLASSES => [
                'TYPO3\CMS\Core\Tests\UnitTestCase' => 'TYPO3\TestingFramework\Core\Unit\UnitTestCase',
                'TYPO3\CMS\Core\Tests\FunctionalTestCase' => 'TYPO3\TestingFramework\Core\Functional\FunctionalTestCase',
            ],
        ]]
    );
};

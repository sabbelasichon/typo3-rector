<?php

declare(strict_types=1);

return static function (
    \Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator
): void {
    $containerConfigurator->import(__DIR__ . '/../services.php');
    $services = $containerConfigurator->services();
    $services->set('namespace_typo3_cms_core_tests_to__typo3_testing_framework_core')
        ->class(\Rector\Renaming\Rector\Name\RenameClassRector::class)->call(
        'configure',
        [[
            \Rector\Renaming\Rector\Name\RenameClassRector::OLD_TO_NEW_CLASSES => [
                \TYPO3\CMS\Core\Tests\UnitTestCase::class => \TYPO3\TestingFramework\Core\Unit\UnitTestCase::class,
                \TYPO3\CMS\Core\Tests\FunctionalTestCase::class => \TYPO3\TestingFramework\Core\Functional\FunctionalTestCase::class,
            ],
        ]]
    );
    $services->set(\Ssch\TYPO3Rector\Rector\v8\v6\MigrateLastPiecesOfDefaultExtrasRector::class);
};

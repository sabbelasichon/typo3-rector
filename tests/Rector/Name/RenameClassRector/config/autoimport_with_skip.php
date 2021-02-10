<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\PostRector\Rector\NameImportingPostRector;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Ssch\TYPO3Rector\Tests\Rector\Name\RenameClassRector\Source\FirstOriginalClass;
use Ssch\TYPO3Rector\Tests\Rector\Name\RenameClassRector\Source\SecondOriginalClass;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();

    $parameters->set(Option::AUTO_IMPORT_NAMES, true);

    $parameters->set(Option::SKIP, [
        NameImportingPostRector::class => ['*_skip_import_names.php'],
    ]);

    $services = $containerConfigurator->services();

    $services->set(RenameClassRector::class)
        ->call('configure', [[
            RenameClassRector::OLD_TO_NEW_CLASSES => [
                FirstOriginalClass::class => SecondOriginalClass::class,
            ],
        ]]);
};

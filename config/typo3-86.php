<?php

declare(strict_types=1);

use Rector\Renaming\Rector\Namespace_\RenameNamespaceRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(RenameNamespaceRector::class)
        ->call('configure', [
            RenameNamespaceRector::OLD_TO_NEW_NAMESPACES => [
                'TYPO3\CMS\Core\Tests' => 'TYPO3\TestingFramework\Core',
            ],
        ]);
};

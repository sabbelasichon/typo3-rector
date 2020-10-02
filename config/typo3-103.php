<?php

declare(strict_types=1);

use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use function Rector\SymfonyPhpConfig\inline_value_objects;
use Ssch\TYPO3Rector\Rector\Core\Resource\SubstituteResourceFactoryRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use TYPO3\CMS\Linkvalidator\Repository\BrokenLinkRepository;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/services.php');

    $services = $containerConfigurator->services();

    $services->set(RenameMethodRector::class)
        ->call('configure', [[
            RenameMethodRector::METHOD_CALL_RENAMES => inline_value_objects([
                new MethodCallRename(
                   BrokenLinkRepository::class,
                   'getNumberOfBrokenLinks',
                   'isLinkTargetBrokenLink'
               ),
            ]),
        ]]);

    $services->set(SubstituteResourceFactoryRector::class);
};

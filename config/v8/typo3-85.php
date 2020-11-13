<?php

declare(strict_types=1);

use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use function Rector\SymfonyPhpConfig\inline_value_objects;
use Ssch\TYPO3Rector\Rector\v8\v5\CharsetConverterToMultiByteFunctionsRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use TYPO3\CMS\Backend\Clipboard\ClipBoard;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(RenameMethodRector::class)
        ->call(
                 'configure',
                 [[
                     RenameMethodRector::METHOD_CALL_RENAMES => inline_value_objects(
                         [new MethodCallRename(ClipBoard::class, 'printContentFromTab', 'getContentFromTab')]
                     ),
                 ]]
             );

    $services->set(CharsetConverterToMultiByteFunctionsRector::class);
};

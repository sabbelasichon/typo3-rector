<?php

declare(strict_types=1);

use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use Ssch\TYPO3Rector\Rector\v10\v3\SubstituteResourceFactoryRector;
use Ssch\TYPO3Rector\Rector\v10\v3\UseClassTypo3VersionRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\SymfonyPhpConfig\ValueObjectInliner;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/../config.php');
    $services = $containerConfigurator->services();
    $services->set(UseClassTypo3VersionRector::class);
    $services->set(RenameMethodRector::class)
        ->call(
            'configure',
            [[
                RenameMethodRector::METHOD_CALL_RENAMES => ValueObjectInliner::inline([
                    new MethodCallRename(
                        'TYPO3\CMS\Linkvalidator\Repository\BrokenLinkRepository',
                        'getNumberOfBrokenLinks',
                        'isLinkTargetBrokenLink'
                    ),
                ]),
            ]]
        );
    $services->set(SubstituteResourceFactoryRector::class);
    $services->set(RenameClassRector::class)
        ->call(
            'configure',
            [[
                RenameClassRector::OLD_TO_NEW_CLASSES => [
                    'TYPO3\CMS\Extbase\Mvc\Web\Request' => 'TYPO3\CMS\Extbase\Mvc\Request',
                    'TYPO3\CMS\Extbase\Mvc\Web\Response' => 'TYPO3\CMS\Extbase\Mvc\Response',
                ],
            ]]
        );
};

<?php

declare(strict_types=1);

use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use function Rector\SymfonyPhpConfig\inline_value_objects;
use Ssch\TYPO3Rector\Rector\v10\v3\SubstituteResourceFactoryRector;
use Ssch\TYPO3Rector\Rector\v10\v3\UseClassTypo3VersionRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use TYPO3\CMS\Extbase\Mvc\Request;
use TYPO3\CMS\Extbase\Mvc\Response;
use TYPO3\CMS\Extbase\Mvc\Web\Request as WebRequest;
use TYPO3\CMS\Extbase\Mvc\Web\Response as WebResponse;
use TYPO3\CMS\Linkvalidator\Repository\BrokenLinkRepository;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/services.php');

    $services = $containerConfigurator->services();

    $services->set(UseClassTypo3VersionRector::class);

    $services->set(RenameMethodRector::class)
        ->call('configure', [
            [
                RenameMethodRector::METHOD_CALL_RENAMES => inline_value_objects([
                    new MethodCallRename(
                        BrokenLinkRepository::class,
                        'getNumberOfBrokenLinks',
                        'isLinkTargetBrokenLink'
                    ),
                ]),
            ],
        ]);

    $services->set(SubstituteResourceFactoryRector::class);

    $services->set(RenameClassRector::class)
        ->call('configure', [
            [
                RenameClassRector::OLD_TO_NEW_CLASSES => [
                    WebRequest::class => Request::class,
                    WebResponse::class => Response::class,
                ],
            ],
        ]);
};

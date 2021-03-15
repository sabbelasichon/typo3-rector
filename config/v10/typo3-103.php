<?php

declare(strict_types=1);

use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use Ssch\TYPO3Rector\Rector\v10\v3\SubstituteResourceFactoryRector;
use Ssch\TYPO3Rector\Rector\v10\v3\UseClassTypo3VersionRector;
use Ssch\TYPO3Rector\TypoScript\Conditions\PIDupinRootlineConditionMatcher;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\SymfonyPhpConfig\ValueObjectInliner;
use TYPO3\CMS\Extbase\Mvc\Request;
use TYPO3\CMS\Extbase\Mvc\Response;
use TYPO3\CMS\Extbase\Mvc\Web\Request as WebRequest;
use TYPO3\CMS\Extbase\Mvc\Web\Response as WebResponse;
use TYPO3\CMS\Linkvalidator\Repository\BrokenLinkRepository;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/../services.php');
    $services = $containerConfigurator->services();
    $services->set(UseClassTypo3VersionRector::class);
    $services->set('rename_broken_link_repository_number_of_broken_links_to_is_link_target_broken_link')
        ->class(RenameMethodRector::class)
        ->call(
        'configure',
        [[
            RenameMethodRector::METHOD_CALL_RENAMES => ValueObjectInliner::inline([
                new MethodCallRename(BrokenLinkRepository::class, 'getNumberOfBrokenLinks', 'isLinkTargetBrokenLink'),
            ]),
        ]]
    );
    $services->set(SubstituteResourceFactoryRector::class);
    $services->set('web_request_to_request_web_response_to_response')
        ->class(RenameClassRector::class)
        ->call(
        'configure',
        [[
            RenameClassRector::OLD_TO_NEW_CLASSES => [
                WebRequest::class => Request::class,
                WebResponse::class => Response::class,
            ],
        ]]
    );

    $services->set(PIDupinRootlineConditionMatcher::class);
};

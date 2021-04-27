<?php

declare(strict_types=1);

use Rector\Renaming\Rector\ClassConstFetch\RenameClassConstFetchRector;

use Rector\Renaming\ValueObject\RenameClassConstFetch;

use Rector\Transform\Rector\MethodCall\MethodCallToStaticCallRector;
use Rector\Transform\ValueObject\MethodCallToStaticCall;
use Ssch\TYPO3Rector\Rector\v7\v6\RenamePiListBrowserResultsRector;
use Ssch\TYPO3Rector\Rector\v7\v6\WrapClickMenuOnIconRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\SymfonyPhpConfig\ValueObjectInliner;
use TYPO3\CMS\IndexedSearch\Utility\LikeWildcard;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/../config.php');
    $services = $containerConfigurator->services();
    $services->set(RenamePiListBrowserResultsRector::class);
    $services->set('document_template_issue_command_to_backend_utility_get_link_to_data_handler_action')
        ->class(MethodCallToStaticCallRector::class)
        ->call(
        'configure',
        [[
            MethodCallToStaticCallRector::METHOD_CALLS_TO_STATIC_CALLS => ValueObjectInliner::inline([
                new MethodCallToStaticCall(
                    'TYPO3\CMS\Backend\Template\DocumentTemplate',
                    'issueCommand',
                    'TYPO3\CMS\Backend\Utility\BackendUtility',
                    'getLinkToDataHandlerAction'
                ),
            ]),
        ]]
    );
    $services->set('search_form_controller_constants_to_like_wildcard_constants')
        ->class(RenameClassConstFetchRector::class)
        ->call(
        'configure',
        [[
            RenameClassConstFetchRector::CLASS_CONSTANT_RENAME => ValueObjectInliner::inline([
                new RenameClassConstFetch(
                    'TYPO3\CMS\IndexedSearch\Controller\SearchFormController',
                    'WILDCARD_LEFT',
                    LikeWildcard::class . '::WILDCARD_LEFT'
                ),
                new RenameClassConstFetch(
                    'TYPO3\CMS\IndexedSearch\Controller\SearchFormController',
                    'WILDCARD_RIGHT',
                    LikeWildcard::class . '::WILDCARD_RIGHT'
                ),
                new RenameClassConstFetch(
                    'TYPO3\CMS\IndexedSearch\Domain\Repository\IndexSearchRepository',
                    'WILDCARD_LEFT',
                    LikeWildcard::class . '::WILDCARD_LEFT'
                ),
                new RenameClassConstFetch(
                    'TYPO3\CMS\IndexedSearch\Domain\Repository\IndexSearchRepository',
                    'WILDCARD_RIGHT',
                    LikeWildcard::class . '::WILDCARD_RIGHT'
                ),
            ]),
        ]]
    );
    $services->set(WrapClickMenuOnIconRector::class);
};

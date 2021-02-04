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
use TYPO3\CMS\Backend\Template\DocumentTemplate;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\IndexedSearch\Controller\SearchFormController;
use TYPO3\CMS\IndexedSearch\Domain\Repository\IndexSearchRepository;
use TYPO3\CMS\IndexedSearch\Utility\LikeWildcard;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/../services.php');
    $services = $containerConfigurator->services();
    $services->set(RenamePiListBrowserResultsRector::class);
    $services->set(MethodCallToStaticCallRector::class)->call('configure', [[
        MethodCallToStaticCallRector::METHOD_CALLS_TO_STATIC_CALLS => ValueObjectInliner::inline([
            new MethodCallToStaticCall(
                DocumentTemplate::class,
                'issueCommand',
                BackendUtility::class,
                'getLinkToDataHandlerAction'
            ),
        ]),
    ]]);
    $services->set(RenameClassConstFetchRector::class)->call('configure', [[
        RenameClassConstFetchRector::CLASS_CONSTANT_RENAME => ValueObjectInliner::inline([
            new RenameClassConstFetch(
                SearchFormController::class,
                'WILDCARD_LEFT',
                LikeWildcard::class . '::WILDCARD_LEFT'
            ),
            new RenameClassConstFetch(
                SearchFormController::class,
                'WILDCARD_RIGHT',
                LikeWildcard::class . '::WILDCARD_RIGHT'
            ),
            new RenameClassConstFetch(
                IndexSearchRepository::class,
                'WILDCARD_LEFT',
                LikeWildcard::class . '::WILDCARD_LEFT'
            ),
            new RenameClassConstFetch(
                IndexSearchRepository::class,
                'WILDCARD_RIGHT',
                LikeWildcard::class . '::WILDCARD_RIGHT'
            ),
        ]),
    ]]);
    $services->set(WrapClickMenuOnIconRector::class);
};

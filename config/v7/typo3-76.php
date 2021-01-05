<?php

declare(strict_types=1);

use Rector\Renaming\Rector\ClassConstFetch\RenameClassConstantRector;
use Rector\Renaming\ValueObject\RenameClassConstant;
use function Rector\SymfonyPhpConfig\inline_value_objects;
use Rector\Transform\Rector\MethodCall\MethodCallToStaticCallRector;
use Rector\Transform\ValueObject\MethodCallToStaticCall;
use Ssch\TYPO3Rector\Rector\v7\v6\RenamePiListBrowserResultsRector;
use Ssch\TYPO3Rector\Rector\v7\v6\WrapClickMenuOnIconRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
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
        MethodCallToStaticCallRector::METHOD_CALLS_TO_STATIC_CALLS => inline_value_objects([
            new MethodCallToStaticCall(
                DocumentTemplate::class,
                'issueCommand',
                BackendUtility::class,
                'getLinkToDataHandlerAction'
            ),
        ]),
    ]]);
    $services->set(RenameClassConstantRector::class)->call('configure', [[
        RenameClassConstantRector::CLASS_CONSTANT_RENAME => inline_value_objects([
            new RenameClassConstant(
                SearchFormController::class,
                'WILDCARD_LEFT',
                LikeWildcard::class . '::WILDCARD_LEFT'
            ),
            new RenameClassConstant(
                SearchFormController::class,
                'WILDCARD_RIGHT',
                LikeWildcard::class . '::WILDCARD_RIGHT'
            ),
            new RenameClassConstant(
                IndexSearchRepository::class,
                'WILDCARD_LEFT',
                LikeWildcard::class . '::WILDCARD_LEFT'
            ),
            new RenameClassConstant(
                IndexSearchRepository::class,
                'WILDCARD_RIGHT',
                LikeWildcard::class . '::WILDCARD_RIGHT'
            ),
        ]),
    ]]);
    $services->set(WrapClickMenuOnIconRector::class);
};

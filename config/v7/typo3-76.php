<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\ClassConstFetch\RenameClassConstFetchRector;
use Rector\Renaming\ValueObject\RenameClassAndConstFetch;
use Rector\Transform\Rector\MethodCall\MethodCallToStaticCallRector;
use Rector\Transform\ValueObject\MethodCallToStaticCall;
use Ssch\TYPO3Rector\Rector\v7\v6\RenamePiListBrowserResultsRector;
use Ssch\TYPO3Rector\Rector\v7\v6\WrapClickMenuOnIconRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');
    $rectorConfig->rule(RenamePiListBrowserResultsRector::class);
    $rectorConfig
        ->ruleWithConfiguration(MethodCallToStaticCallRector::class, [
            new MethodCallToStaticCall(
                'TYPO3\CMS\Backend\Template\DocumentTemplate',
                'issueCommand',
                'TYPO3\CMS\Backend\Utility\BackendUtility',
                'getLinkToDataHandlerAction'
            ),
        ]);
    $rectorConfig
        ->ruleWithConfiguration(RenameClassConstFetchRector::class, [
            new RenameClassAndConstFetch(
                'TYPO3\CMS\IndexedSearch\Controller\SearchFormController',
                'WILDCARD_LEFT',
                'TYPO3\CMS\IndexedSearch\Utility\LikeWildcard',
                'WILDCARD_LEFT'
            ),
            new RenameClassAndConstFetch(
                'TYPO3\CMS\IndexedSearch\Controller\SearchFormController',
                'WILDCARD_RIGHT',
                'TYPO3\CMS\IndexedSearch\Utility\LikeWildcard',
                'WILDCARD_RIGHT'
            ),
            new RenameClassAndConstFetch(
                'TYPO3\CMS\IndexedSearch\Domain\Repository\IndexSearchRepository',
                'WILDCARD_LEFT',
                'TYPO3\CMS\IndexedSearch\Utility\LikeWildcard',
                'WILDCARD_LEFT'
            ),
            new RenameClassAndConstFetch(
                'TYPO3\CMS\IndexedSearch\Domain\Repository\IndexSearchRepository',
                'WILDCARD_RIGHT',
                'TYPO3\CMS\IndexedSearch\Utility\LikeWildcard',
                'WILDCARD_RIGHT'
            ),
        ]);
    $rectorConfig->rule(WrapClickMenuOnIconRector::class);
};

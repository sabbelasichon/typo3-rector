<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\ValueObject\MethodCallRename;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../config.php');
    $rectorConfig->ruleWithConfiguration(
        RenameMethodRector::class,
        [
            new MethodCallRename(
                'ApacheSolrForTypo3\Solr\System\Url\UrlHelper',
                'removeQueryParameter',
                'withoutQueryParameter'
            ),
            new MethodCallRename('ApacheSolrForTypo3\Solr\System\Url\UrlHelper', 'getUrl', '__toString'),
        ]
    );
};

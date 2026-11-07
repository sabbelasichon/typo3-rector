<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v3\MigrateCacheActionHrefToEndpointRector\Fixture;

use TYPO3\CMS\Backend\Backend\Event\ModifyClearCacheActionsEvent;

final class MyMigratedClearCacheActionsListener
{
    public function __invoke(ModifyClearCacheActionsEvent $event, array $cacheAction): void
    {
        $event->addCacheAction([
            'id' => 'my_custom_cache',
            'endpoint' => '/typo3/ajax/my-cache/clear',
        ]);
        $event->addCacheAction([
            'id' => 'my_both_cache',
            'href' => '/typo3/ajax/my-cache/clear',
            'endpoint' => '/typo3/ajax/my-cache/clear',
        ]);
        $event->addCacheAction($cacheAction);
        $event->setCacheActions([$cacheAction]);
    }
}

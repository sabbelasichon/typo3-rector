<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v3\MigrateCacheActionHrefToEndpointRector\Fixture;

use TYPO3\CMS\Backend\Backend\Event\ModifyClearCacheActionsEvent;
use TYPO3\CMS\Backend\Routing\UriBuilder;

final class MySetClearCacheActionsListener
{
    public function __invoke(ModifyClearCacheActionsEvent $event, UriBuilder $uriBuilder, string $url): void
    {
        $event->setCacheActions([
            [
                'id' => 'first_cache',
                'href' => $uriBuilder->buildUriFromRoute('ajax_first_cache_clear'),
            ],
            [
                'id' => 'second_cache',
                'href' => $url,
            ],
            [
                'id' => 'third_cache',
                'endpoint' => '/typo3/ajax/third-cache/clear',
            ],
        ]);
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v3\MigrateCacheActionHrefToEndpointRector\Fixture;

use TYPO3\CMS\Backend\Backend\Event\ModifyClearCacheActionsEvent;
use TYPO3\CMS\Backend\Routing\UriBuilder;

final class MySetClearCacheActionsListener
{
    public function __invoke(ModifyClearCacheActionsEvent $event, UriBuilder $uriBuilder, string $url): void
    {
        $event->setCacheActions([
            [
                'id' => 'first_cache',
                'endpoint' => (string) $uriBuilder->buildUriFromRoute('ajax_first_cache_clear'),
            ],
            [
                'id' => 'second_cache',
                'endpoint' => $url,
            ],
            [
                'id' => 'third_cache',
                'endpoint' => '/typo3/ajax/third-cache/clear',
            ],
        ]);
    }
}

?>

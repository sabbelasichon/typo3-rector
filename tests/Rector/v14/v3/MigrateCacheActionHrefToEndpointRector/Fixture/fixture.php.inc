<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v3\MigrateCacheActionHrefToEndpointRector\Fixture;

use TYPO3\CMS\Backend\Backend\Event\ModifyClearCacheActionsEvent;
use TYPO3\CMS\Backend\Routing\UriBuilder;

final class MyClearCacheActionsListener
{
    private UriBuilder $uriBuilder;

    public function __construct(UriBuilder $uriBuilder)
    {
        $this->uriBuilder = $uriBuilder;
    }

    public function __invoke(ModifyClearCacheActionsEvent $event): void
    {
        $event->addCacheAction([
            'id' => 'my_custom_cache',
            'href' => $this->uriBuilder->buildUriFromRoute('ajax_my_cache_clear'),
            'iconIdentifier' => 'actions-system-cache-clear',
            'title' => 'Clear my cache',
            'description' => 'Optional description',
            'severity' => 'notice',
        ]);
        $event->addCacheAction([
            'id' => 'my_other_cache',
            'href' => '/typo3/ajax/my-other-cache/clear',
            'title' => 'Clear my other cache',
        ]);
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v3\MigrateCacheActionHrefToEndpointRector\Fixture;

use TYPO3\CMS\Backend\Backend\Event\ModifyClearCacheActionsEvent;
use TYPO3\CMS\Backend\Routing\UriBuilder;

final class MyClearCacheActionsListener
{
    private UriBuilder $uriBuilder;

    public function __construct(UriBuilder $uriBuilder)
    {
        $this->uriBuilder = $uriBuilder;
    }

    public function __invoke(ModifyClearCacheActionsEvent $event): void
    {
        $event->addCacheAction([
            'id' => 'my_custom_cache',
            'endpoint' => (string) $this->uriBuilder->buildUriFromRoute('ajax_my_cache_clear'),
            'iconIdentifier' => 'actions-system-cache-clear',
            'title' => 'Clear my cache',
            'description' => 'Optional description',
            'severity' => 'notice',
        ]);
        $event->addCacheAction([
            'id' => 'my_other_cache',
            'endpoint' => '/typo3/ajax/my-other-cache/clear',
            'title' => 'Clear my other cache',
        ]);
    }
}

?>

<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v3\MigrateCacheActionHrefToEndpointRector\Fixture;

final class MyOtherCacheActionCollector
{
    public function addCacheAction(array $cacheAction): void
    {
    }

    public function collect(): void
    {
        $this->addCacheAction([
            'id' => 'my_custom_cache',
            'href' => '/typo3/ajax/my-cache/clear',
        ]);
    }
}

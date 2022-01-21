<?php

namespace Ssch\TYPO3Rector\Tests;

use \TYPO3\CMS\Core\Cache\CacheManager;

class Service
{
    private CacheManager $cacheManager;

    public function injectCacheManager(CacheManager $cacheManager): void
    {
        $this->cacheManager = $cacheManager;
    }
}
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests;

use \TYPO3\CMS\Core\Cache\CacheManager;

class Service
{
    private CacheManager $cacheManager;

    public function __construct(CacheManager $cacheManager)
    {
        $this->cacheManager = $cacheManager;
    }
}
?>

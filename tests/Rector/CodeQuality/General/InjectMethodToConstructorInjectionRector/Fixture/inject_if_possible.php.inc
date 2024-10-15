<?php

namespace Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\InjectMethodToConstructorInjectionRector\Fixture;

use TYPO3\CMS\Core\Cache\CacheManager;

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

namespace Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\InjectMethodToConstructorInjectionRector\Fixture;

use TYPO3\CMS\Core\Cache\CacheManager;

class Service
{
    private CacheManager $cacheManager;

    public function __construct(CacheManager $cacheManager)
    {
        $this->cacheManager = $cacheManager;
    }
}
?>

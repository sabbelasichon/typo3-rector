<?php

namespace Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\InjectMethodToConstructorInjectionRector\Fixture;

use TYPO3\CMS\Core\Cache\CacheManager;

class Service
{
    private CacheManager $cacheManagerWithDifferentVariableName;

    public function injectCacheManager(CacheManager $cacheManager): void
    {
        $this->cacheManagerWithDifferentVariableName = $cacheManager;
    }
}
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\InjectMethodToConstructorInjectionRector\Fixture;

use TYPO3\CMS\Core\Cache\CacheManager;

class Service
{
    private CacheManager $cacheManagerWithDifferentVariableName;

    public function __construct(CacheManager $cacheManagerWithDifferentVariableName)
    {
        $this->cacheManagerWithDifferentVariableName = $cacheManagerWithDifferentVariableName;
    }
}
?>

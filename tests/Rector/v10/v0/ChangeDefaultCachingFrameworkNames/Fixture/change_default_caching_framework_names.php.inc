<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Cache\CacheManager;

class ChangeDefaultCachingFrameworkNames
{
    public function method(): void
    {
        $cacheManager = GeneralUtility::makeInstance(CacheManager::class);
        $cacheManager->getCache('cache_core');
        $cacheManager->getCache('cache_hash');
        $cacheManager->getCache('cache_pages');
        $cacheManager->getCache('cache_pagesection');
        $cacheManager->getCache('cache_runtime');
        $cacheManager->getCache('cache_rootline');
        $cacheManager->getCache('cache_imagesizes');
    }
}
?>
-----
<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Cache\CacheManager;

class ChangeDefaultCachingFrameworkNames
{
    public function method(): void
    {
        $cacheManager = GeneralUtility::makeInstance(CacheManager::class);
        $cacheManager->getCache('core');
        $cacheManager->getCache('hash');
        $cacheManager->getCache('pages');
        $cacheManager->getCache('pagesection');
        $cacheManager->getCache('runtime');
        $cacheManager->getCache('rootline');
        $cacheManager->getCache('imagesizes');
    }
}
?>

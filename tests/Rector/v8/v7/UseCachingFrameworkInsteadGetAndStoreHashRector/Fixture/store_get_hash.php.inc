<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v8\v7\UseCachingFrameworkInsteadGetAndStoreHashRector\Fixture;

use TYPO3\CMS\Frontend\Page\PageRepository;

class FooBarBaz
{
    public function some($hash, $data, $ident)
    {
        $GLOBALS['TSFE']->sys_page->storeHash($hash, $data, $ident);
        $GLOBALS['TSFE']->sys_page->storeHash($hash, $data, $ident, '1000');
        PageRepository::storeHash($hash, $data, $ident);
        $lifetime = '1000';
        PageRepository::storeHash($hash, $data, $ident, $lifetime);
        $hashContent = PageRepository::getHash($hash);
        $hashContent2 = $GLOBALS['TSFE']->sys_page->getHash($hash);
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v8\v7\UseCachingFrameworkInsteadGetAndStoreHashRector\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Frontend\Page\PageRepository;

class FooBarBaz
{
    public function some($hash, $data, $ident)
    {
        GeneralUtility::makeInstance(CacheManager::class)->getCache('cache_hash')->set($hash, $data, ['ident_' . $ident], 0);
        GeneralUtility::makeInstance(CacheManager::class)->getCache('cache_hash')->set($hash, $data, ['ident_' . $ident], (int) '1000');
        GeneralUtility::makeInstance(CacheManager::class)->getCache('cache_hash')->set($hash, $data, ['ident_' . $ident], 0);
        $lifetime = '1000';
        GeneralUtility::makeInstance(CacheManager::class)->getCache('cache_hash')->set($hash, $data, ['ident_' . $ident], (int) $lifetime);
        $hashContent = GeneralUtility::makeInstance(CacheManager::class)->getCache('cache_hash')->get($hash);
        $hashContent2 = GeneralUtility::makeInstance(CacheManager::class)->getCache('cache_hash')->get($hash);
    }
}

?>

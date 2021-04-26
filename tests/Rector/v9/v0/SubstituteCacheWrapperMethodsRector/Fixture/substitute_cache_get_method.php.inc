<?php

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$hash = 'foo';
$content = BackendUtility::getHash($hash);

?>
-----
<?php

use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$hash = 'foo';
$cacheManager = GeneralUtility::makeInstance(CacheManager::class);
$cacheEntry = $cacheManager->getCache('cache_hash')->get($hash);
$hashContent = null;
if ($cacheEntry) {
    $hashContent = $cacheEntry;
}
$content = $hashContent;

?>

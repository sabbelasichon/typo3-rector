<?php

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$hash = 'foo';
$ident = 'ident';

BackendUtility::storeHash($hash, 'data', $ident);

?>
-----
<?php

use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$hash = 'foo';
$ident = 'ident';
$cacheManager = GeneralUtility::makeInstance(CacheManager::class);
$cacheManager->getCache('cache_hash')->set($hash, 'data', ['ident_' . $ident], 0);

?>

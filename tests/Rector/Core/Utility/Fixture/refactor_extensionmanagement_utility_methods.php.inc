<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

final class ClassWithOldExtensionManagementUtilityMethods
{
    public function method(): void
    {
        ExtensionManagementUtility::siteRelPath('news');
        ExtensionManagementUtility::isLoaded('news');
        ExtensionManagementUtility::isLoaded('news', true);
        ExtensionManagementUtility::removeCacheFiles();
    }
}

?>
-----
<?php

use TYPO3\CMS\Core\Utility\PathUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Core\CacheManager;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

final class ClassWithOldExtensionManagementUtilityMethods
{
    public function method(): void
    {
        PathUtility::stripPathSitePrefix(ExtensionManagementUtility::extPath('news'));
        ExtensionManagementUtility::isLoaded('news');
        ExtensionManagementUtility::isLoaded('news');
        GeneralUtility::makeInstance(CacheManager::class)->flushCachesInGroup('system');
    }
}

?>

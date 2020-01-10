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

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

final class ClassWithOldExtensionManagementUtilityMethods
{
    public function method(): void
    {
        \TYPO3\CMS\Core\Utility\PathUtility::stripPathSitePrefix(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('news'));
        ExtensionManagementUtility::isLoaded('news');
        ExtensionManagementUtility::isLoaded('news');
        \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Core\CacheManager::class)->flushCachesInGroup('system');
    }
}

?>

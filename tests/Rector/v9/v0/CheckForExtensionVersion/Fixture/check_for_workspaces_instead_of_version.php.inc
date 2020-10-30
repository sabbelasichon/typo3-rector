<?php

use TYPO3\CMS\Core\Package\PackageManager;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

if(ExtensionManagementUtility::isLoaded('version')) {
}

if(ExtensionManagementUtility::isLoaded('news')) {
}

$packageManager = GeneralUtility::makeInstance(PackageManager::class);
if($packageManager->isPackageActive('version')) {
}

if($packageManager->isPackageActive('news')) {
}

?>
-----
<?php

use TYPO3\CMS\Core\Package\PackageManager;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

if(ExtensionManagementUtility::isLoaded('workspaces')) {
}

if(ExtensionManagementUtility::isLoaded('news')) {
}

$packageManager = GeneralUtility::makeInstance(PackageManager::class);
if($packageManager->isPackageActive('workspaces')) {
}

if($packageManager->isPackageActive('news')) {
}

?>

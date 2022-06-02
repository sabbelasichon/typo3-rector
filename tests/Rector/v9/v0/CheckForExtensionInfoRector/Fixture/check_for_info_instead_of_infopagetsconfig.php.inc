<?php

use TYPO3\CMS\Core\Package\PackageManager;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

if (ExtensionManagementUtility::isLoaded('info_pagetsconfig')) {
}

if (ExtensionManagementUtility::isLoaded('news')) {
}

$packageManager = GeneralUtility::makeInstance(PackageManager::class);
if ($packageManager->isPackageActive('info_pagetsconfig')) {
}

if ($packageManager->isPackageActive('news')) {
}

?>
-----
<?php

use TYPO3\CMS\Core\Package\PackageManager;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

if (ExtensionManagementUtility::isLoaded('info')) {
}

if (ExtensionManagementUtility::isLoaded('news')) {
}

$packageManager = GeneralUtility::makeInstance(PackageManager::class);
if ($packageManager->isPackageActive('info')) {
}

if ($packageManager->isPackageActive('news')) {
}

?>

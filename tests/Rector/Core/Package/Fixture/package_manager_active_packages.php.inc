<?php

$extensionList = $GLOBALS['TYPO3_LOADED_EXT'];

?>
-----
<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Package\PackageManager;
$extensionList = GeneralUtility::makeInstance(PackageManager::class)->getActivePackages();

<?php

$extensionList = $GLOBALS['TYPO3_LOADED_EXT'];

?>
-----
<?php

$extensionList = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Package\PackageManager::class)->getActivePackages();

?>

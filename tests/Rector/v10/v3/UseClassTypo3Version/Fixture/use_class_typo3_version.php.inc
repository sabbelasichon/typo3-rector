<?php

$typo3Version = TYPO3_version;
$typo3Branch = TYPO3_branch;

?>
-----
<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Information\Typo3Version;
$typo3Version = GeneralUtility::makeInstance(Typo3Version::class)->getVersion();
$typo3Branch = GeneralUtility::makeInstance(Typo3Version::class)->getBranch();

?>

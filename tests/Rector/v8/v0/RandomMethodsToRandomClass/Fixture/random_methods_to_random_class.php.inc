<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;

$randomBytes = GeneralUtility::generateRandomBytes();
$randomHex = GeneralUtility::getRandomHexString();

?>
-----
<?php

use TYPO3\CMS\Core\Crypto\Random;
use TYPO3\CMS\Core\Utility\GeneralUtility;
$randomBytes = GeneralUtility::makeInstance(Random::class)->generateRandomBytes();
$randomHex = GeneralUtility::makeInstance(Random::class)->generateRandomHexString();

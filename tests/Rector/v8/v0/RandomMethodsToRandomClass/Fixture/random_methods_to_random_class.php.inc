<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;

$randomBytes = GeneralUtility::generateRandomBytes(8);
$count = 7;
$randomHex = GeneralUtility::getRandomHexString($count);

?>
-----
<?php

use TYPO3\CMS\Core\Crypto\Random;
use TYPO3\CMS\Core\Utility\GeneralUtility;
$randomBytes = GeneralUtility::makeInstance(Random::class)->generateRandomBytes(8);
$count = 7;
$randomHex = GeneralUtility::makeInstance(Random::class)->generateRandomHexString($count);

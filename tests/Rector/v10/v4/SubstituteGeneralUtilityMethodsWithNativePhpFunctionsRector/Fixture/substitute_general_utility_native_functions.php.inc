<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;

$hex = '127.0.0.1';
GeneralUtility::IPv6Hex2Bin($hex);
$bin = $packed = chr(127) . chr(0) . chr(0) . chr(1);
GeneralUtility::IPv6Bin2Hex($bin);
$address = '127.0.0.1';
GeneralUtility::compressIPv6($address);
GeneralUtility::milliseconds();

?>
-----
<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;

$hex = '127.0.0.1';
inet_pton($hex);
$bin = $packed = chr(127) . chr(0) . chr(0) . chr(1);
inet_ntop($bin);
$address = '127.0.0.1';
inet_ntop(inet_pton($address));
round(microtime(true) * 1000);

?>

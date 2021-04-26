<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;

$microtime = GeneralUtility::convertMicrotime('10 00');

?>
-----
<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;
$parts = explode(' ', '10 00');

$microtime = round(($parts[0] + $parts[1]) * 1000);

?>

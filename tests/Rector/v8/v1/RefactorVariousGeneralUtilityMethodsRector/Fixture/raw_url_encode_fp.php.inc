<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;

$url = 'https://www.domain.com/';
$url = GeneralUtility::rawUrlEncodeFP($url);

?>
-----
<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;

$url = 'https://www.domain.com/';
$url = str_replace('%2F', '/', rawurlencode($url));

?>

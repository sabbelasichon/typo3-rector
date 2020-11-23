<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;

$url = 'https://www.domain.com/';
$url = GeneralUtility::rawUrlEncodeJS($url);

?>
-----
<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;

$url = 'https://www.domain.com/';
$url = str_replace('%20', ' ', rawurlencode($url));

?>

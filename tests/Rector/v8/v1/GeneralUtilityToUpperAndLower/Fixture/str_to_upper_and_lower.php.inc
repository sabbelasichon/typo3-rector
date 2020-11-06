<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;

$toUpper = GeneralUtility::strtoupper('foo');
$toLower = GeneralUtility::strtolower('FOO');

?>
-----
<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;

$toUpper = mb_strtoupper('foo', 'utf-8');
$toLower = mb_strtolower('FOO', 'utf-8');

?>

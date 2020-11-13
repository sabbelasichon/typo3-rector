<?php

use TYPO3\CMS\Core\Charset\CharsetConverter;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$charsetConverter = GeneralUtility::makeInstance(CharsetConverter::class);
$charsetConverter->strlen('utf-8', 'foo');
$charsetConverter->convCapitalize('utf-8', 'foo');
$charsetConverter->substr('utf-8', 'foo', 0);
$charsetConverter->substr('utf-8', 'foo', 0, 5);
$charsetConverter->conv_case('utf-8', 'foo', 'toLower');
$charsetConverter->conv_case('utf-8', 'foo', 'toUpper');
$charsetConverter->utf8_strpos('foo', 'bar');
$charsetConverter->utf8_strpos('foo', 'bar', 1);
$charsetConverter->utf8_strrpos('foo', 'bar');

?>
-----
<?php

use TYPO3\CMS\Core\Charset\CharsetConverter;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$charsetConverter = GeneralUtility::makeInstance(CharsetConverter::class);
mb_strlen('foo', 'utf-8');
mb_convert_case('foo', MB_CASE_TITLE, 'utf-8');
mb_substr('foo', 0, null, 'utf-8');
mb_substr('foo', 0, 5, 'utf-8');
mb_strtolower('foo', 'utf-8');
mb_strtoupper('foo', 'utf-8');
mb_strpos('foo', 'bar', 0, 'utf-8');
mb_strpos('foo', 'bar', 1, 'utf-8');
mb_strrpos('foo', 'bar', 'utf-8');

?>

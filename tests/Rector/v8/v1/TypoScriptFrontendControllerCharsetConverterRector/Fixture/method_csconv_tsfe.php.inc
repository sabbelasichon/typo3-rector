<?php

use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

$from = 'iso-8859-15';
$string = 'My string';
$output1 = $GLOBALS['TSFE']->csConv($string, $from);
$output2 = $GLOBALS['TSFE']->csConv($string);

/** @var TypoScriptFrontendController $typoscriptFrontendController */
$typoscriptFrontendController = $GLOBALS['TSFE'];
$output3 = $typoscriptFrontendController->csConv($string, $from);
$typoscriptFrontendController->setContentType('text/text');

?>
-----
<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Charset\CharsetConverter;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

$from = 'iso-8859-15';
$string = 'My string';
$charsetConverter = GeneralUtility::makeInstance(CharsetConverter::class);
$output1 = $charsetConverter->conv($string, $charsetConverter->parse_charset($from), 'utf-8');
$output2 = $string;

/** @var TypoScriptFrontendController $typoscriptFrontendController */
$typoscriptFrontendController = $GLOBALS['TSFE'];
$charsetConverter = GeneralUtility::makeInstance(CharsetConverter::class);
$output3 = $charsetConverter->conv($string, $charsetConverter->parse_charset($from), 'utf-8');
$typoscriptFrontendController->setContentType('text/text');

?>

<?php

$output = $GLOBALS['TSFE']->csConvObj->conv_case('utf-8', 'foobar', 'lower');

?>
-----
<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Charset\CharsetConverter;
$charsetConverter = GeneralUtility::makeInstance(CharsetConverter::class);
$output = $charsetConverter->conv_case('utf-8', 'foobar', 'lower');

<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;

$xml1 = GeneralUtility::array2xml_cs(['foo' => 'bar']);
$xml2 = GeneralUtility::array2xml_cs(['foo' => 'bar'], 'doctag');
$xml3 = GeneralUtility::array2xml_cs(['foo' => 'bar'], 'doctag', ['foo' => 'bar']);
$xml4 = GeneralUtility::array2xml_cs(['foo' => 'bar'], 'doctag', ['foo' => 'bar'], 'iso-8859-1');

?>
-----
<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;

$xml1 = '<?xml version="1.0" encoding="' . htmlspecialchars('utf-8') . '" standalone="yes" ?>' . LF . GeneralUtility::array2xml(['foo' => 'bar'], '', 0, 'phparray', 0, []);
$xml2 = '<?xml version="1.0" encoding="' . htmlspecialchars('utf-8') . '" standalone="yes" ?>' . LF . GeneralUtility::array2xml(['foo' => 'bar'], '', 0, 'doctag', 0, []);
$xml3 = '<?xml version="1.0" encoding="' . htmlspecialchars('utf-8') . '" standalone="yes" ?>' . LF . GeneralUtility::array2xml(['foo' => 'bar'], '', 0, 'doctag', 0, ['foo' => 'bar']);
$xml4 = '<?xml version="1.0" encoding="' . htmlspecialchars('iso-8859-1') . '" standalone="yes" ?>' . LF . GeneralUtility::array2xml(['foo' => 'bar'], '', 0, 'doctag', 0, ['foo' => 'bar']);

?>

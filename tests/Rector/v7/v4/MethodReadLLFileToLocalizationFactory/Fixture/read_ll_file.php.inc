<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;

$locallangs = GeneralUtility::readLLfile('EXT:foo/locallang.xml', 'de');

?>
-----
<?php

use TYPO3\CMS\Core\Localization\LocalizationFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$locallangs = GeneralUtility::makeInstance(LocalizationFactory::class)->getParsedData('EXT:foo/locallang.xml', 'de');

?>

<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;

/** @var \TYPO3\CMS\Lang\LanguageService $languageService1 */

$languageService1 = GeneralUtility::makeInstance(\TYPO3\CMS\Lang\LanguageService::class);

?>
-----
<?php

use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/** @var LanguageService $languageService1 */
$languageService1 = GeneralUtility::makeInstance(LanguageService::class);

?>

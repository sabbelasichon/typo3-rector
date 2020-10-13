<?php

$forceTemplateParsing = $GLOBALS['TSFE']->forceTemplateParsing;

$GLOBALS['TSFE']->forceTemplateParsing = true;

?>
-----
<?php

$forceTemplateParsing = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Context\Context::class)->getPropertyFromAspect('typoscript', 'forcedTemplateParsing');

\TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Context\Context::class)->setAspect('typoscript', \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Context\TypoScriptAspect::class, true));

?>

<?php

$myvariable = $GLOBALS['TSFE']->forceTemplateParsing;
$myvariable2 = $GLOBALS['TSFE']->tmpl->forceTemplateParsing;

$GLOBALS['TSFE']->forceTemplateParsing = true;
$GLOBALS['TSFE']->tmpl->forceTemplateParsing = true;

?>
-----
<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Context\TypoScriptAspect;
$myvariable = GeneralUtility::makeInstance(Context::class)->getPropertyFromAspect('typoscript', 'forcedTemplateParsing');
$myvariable2 = GeneralUtility::makeInstance(Context::class)->getPropertyFromAspect('typoscript', 'forcedTemplateParsing');

GeneralUtility::makeInstance(Context::class)->setAspect('typoscript', GeneralUtility::makeInstance(TypoScriptAspect::class, true));
GeneralUtility::makeInstance(Context::class)->setAspect('typoscript', GeneralUtility::makeInstance(TypoScriptAspect::class, true));

?>

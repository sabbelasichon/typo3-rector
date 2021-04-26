<?php

use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/** @var TypoScriptFrontendController $typoScriptFrontendController */
$typoScriptFrontendController = $GLOBALS['TSFE'];
$shortCutPage1 = $typoScriptFrontendController->getPageShortcut('shortcut', 1, 1);

?>
-----
<?php

use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/** @var TypoScriptFrontendController $typoScriptFrontendController */
$typoScriptFrontendController = $GLOBALS['TSFE'];
$shortCutPage1 = $typoScriptFrontendController->sys_page->getPageShortcut('shortcut', 1, 1);

?>

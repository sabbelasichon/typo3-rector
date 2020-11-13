<?php

$shortCutPage1 = $GLOBALS['TSFE']->getPageShortcut('shortcut', 1, 1);
$shortCutPage2 = $GLOBALS['TSFE']->getPageShortcut('shortcut', 1, 1, 50);

?>
-----
<?php

$shortCutPage1 = $GLOBALS['TSFE']->sys_page->getPageShortcut('shortcut', 1, 1);
$shortCutPage2 = $GLOBALS['TSFE']->sys_page->getPageShortcut('shortcut', 1, 1, 50);

?>

<?php

$theFirstPage = $GLOBALS['TSFE']->sys_page->getFirstWebPage(0);

?>
-----
<?php

$theFirstPage = reset($GLOBALS['TSFE']->sys_page->getMenu(0, 'uid', 'sorting', '', false));

?>

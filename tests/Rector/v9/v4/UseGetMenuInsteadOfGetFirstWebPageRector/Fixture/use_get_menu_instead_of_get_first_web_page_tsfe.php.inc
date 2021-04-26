<?php

$theFirstPage = $GLOBALS['TSFE']->sys_page->getFirstWebPage(0);

?>
-----
<?php

$rootLevelPages = $GLOBALS['TSFE']->sys_page->getMenu(0, 'uid', 'sorting', '', false);
if (!empty($rootLevelPages)) {
    $theFirstPage = reset($rootLevelPages);
}

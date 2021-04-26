<?php

if ($GLOBALS['TSFE']->sys_language_isocode) {
    $GLOBALS['LANG']->init($GLOBALS['TSFE']->sys_language_isocode);
}

$GLOBALS['TSFE']->sys_language_isocode = 'de';

?>
-----
<?php

if ($GLOBALS['TSFE']->getLanguage()->getTwoLetterIsoCode()) {
    $GLOBALS['LANG']->init($GLOBALS['TSFE']->getLanguage()->getTwoLetterIsoCode());
}

$GLOBALS['TSFE']->sys_language_isocode = 'de';

?>

<?php

if ($GLOBALS['TSFE']->sys_language_isocode) {
    $GLOBALS['LANG']->init($GLOBALS['TSFE']->sys_language_isocode);
}

$userObject = $GLOBALS['TSFE']->fe_user->user;

?>
-----
<?php

if ($GLOBALS['TSFE']->getLanguage()->getTwoLetterIsoCode()) {
    $GLOBALS['LANG']->init($GLOBALS['TSFE']->getLanguage()->getTwoLetterIsoCode());
}

$userObject = $GLOBALS['TSFE']->fe_user->user;

?>

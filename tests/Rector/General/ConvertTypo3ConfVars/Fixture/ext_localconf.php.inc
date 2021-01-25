<?php

if (TYPO3_MODE == 'FE') {
    $TYPO3_CONF_VARS['SC_OPTIONS']['t3lib/class.t3lib_userauth.php']['postUserLookUp']['foo'] = 'Aoe\\FeloginBruteforceProtection\\Hooks\\UserAuth\\PostUserLookUp->handlePostUserLookUp';
}

?>
-----
<?php

if (TYPO3_MODE == 'FE') {
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_userauth.php']['postUserLookUp']['foo'] = 'Aoe\\FeloginBruteforceProtection\\Hooks\\UserAuth\\PostUserLookUp->handlePostUserLookUp';
}

?>

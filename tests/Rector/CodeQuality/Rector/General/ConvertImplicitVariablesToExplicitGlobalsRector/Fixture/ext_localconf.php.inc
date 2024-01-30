<?php

if (TYPO3_MODE == 'FE') {
    $TYPO3_CONF_VARS['SC_OPTIONS']['t3lib/class.t3lib_userauth.php']['postUserLookUp']['foo'] = 'Aoe\\FeloginBruteforceProtection\\Hooks\\UserAuth\\PostUserLookUp->handlePostUserLookUp';

    $TCA['tt_content']['types']['content_type']['showitems'] = 'header,subheader';
}

?>
-----
<?php

if (TYPO3_MODE == 'FE') {
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_userauth.php']['postUserLookUp']['foo'] = 'Aoe\\FeloginBruteforceProtection\\Hooks\\UserAuth\\PostUserLookUp->handlePostUserLookUp';

    $GLOBALS['TCA']['tt_content']['types']['content_type']['showitems'] = 'header,subheader';
}

?>

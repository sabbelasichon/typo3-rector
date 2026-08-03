<?php

$TYPO3_CONF_VARS['SC_OPTIONS']['t3lib/class.t3lib_userauth.php']['postUserLookUp']['foo'] = 'MyExtension\\Hooks\\UserAuth\\MyClass->handlePostUserLookUp';
$TCA['tt_content']['types']['content_type']['showitems'] = 'header,subheader';

?>
-----
<?php

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_userauth.php']['postUserLookUp']['foo'] = 'MyExtension\\Hooks\\UserAuth\\MyClass->handlePostUserLookUp';
$GLOBALS['TCA']['tt_content']['types']['content_type']['showitems'] = 'header,subheader';

?>

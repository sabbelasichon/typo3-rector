<?php

$extensionConfiguration2 = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['foo'], ['allowed_classes' => false]);

$_EXTKEY = 'foo';
call_user_func(static function () use($_EXTKEY) {
    $_EXTCONF = isset($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$_EXTKEY]) ? unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$_EXTKEY], ['allowed_classes' => false]) : [];
});

?>
-----
<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;

use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
$extensionConfiguration2 = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('foo');
$_EXTKEY = 'foo';
call_user_func(static function () use($_EXTKEY) {
    $_EXTCONF = isset($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$_EXTKEY]) ? GeneralUtility::makeInstance(ExtensionConfiguration::class)->get($_EXTKEY) : [];
});

?>

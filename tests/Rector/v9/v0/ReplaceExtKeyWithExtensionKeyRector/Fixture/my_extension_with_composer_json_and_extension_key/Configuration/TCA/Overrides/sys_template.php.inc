<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

// must be defined
$_EXTKEY = 'xyz';

if ( ! defined('TYPO3_MODE')) {
    die('Access denied.');
}

ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Example');

?>
-----
<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

// must be defined
$_EXTKEY = 'xyz';

if ( ! defined('TYPO3_MODE')) {
    die('Access denied.');
}

ExtensionManagementUtility::addStaticFile('Overrides', 'Configuration/TypoScript', 'Example');

?>

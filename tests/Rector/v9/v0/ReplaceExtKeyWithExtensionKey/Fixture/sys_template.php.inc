<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

// must be defined
$_EXTKEY = '___';

if ( ! defined('TYPO3_MODE')) {
    die('Access denied.');
}

ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Example');

?>
-----
<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

// must be defined
'_temp_fixture_easy_testing' = '___';

if ( ! defined('TYPO3_MODE')) {
    die('Access denied.');
}

ExtensionManagementUtility::addStaticFile('_temp_fixture_easy_testing', 'Configuration/TypoScript', 'Example');

?>

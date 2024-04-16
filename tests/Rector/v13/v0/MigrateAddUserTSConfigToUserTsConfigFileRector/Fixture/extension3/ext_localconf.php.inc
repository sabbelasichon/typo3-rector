<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateAddUserTSConfigToUserTsConfigFileRector\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;

$variable = 'extension3';
$_EXTKEY = 'extension3';

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addUserTSConfig(
    '@import "EXT:' . $variable . '/Configuration/TSconfig/*/*.tsconfig"'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addUserTSConfig(
    GeneralUtility::deprecationLog('foo')
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addUserTSConfig(
    '@import "EXT:extension3/Configuration/TSconfig/*/*.tsconfig"'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addUserTSConfig(
    '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:' . $_EXTKEY . '/Configuration/TSconfig/User.txt">'
);

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateAddUserTSConfigToUserTsConfigFileRector\Fixture;

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$variable = 'extension3';
$_EXTKEY = 'extension3';

ExtensionManagementUtility::addUserTSConfig(
    GeneralUtility::deprecationLog('foo')
);

?>

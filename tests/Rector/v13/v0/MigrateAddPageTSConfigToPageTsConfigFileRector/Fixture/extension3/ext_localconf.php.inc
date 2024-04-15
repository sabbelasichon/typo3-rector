<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateAddPageTSConfigToPageTsConfigFileRector\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;

$variable = 'ext_key';

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
    '@import "EXT:' . $variable . '/Configuration/TSconfig/*/*.tsconfig"'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
    GeneralUtility::deprecationLog('foo')
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
    '@import "EXT:ext_key/Configuration/TSconfig/*/*.tsconfig"'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:' . $_EXTKEY . '/Configuration/TSconfig/Page.txt">');

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateAddPageTSConfigToPageTsConfigFileRector\Fixture;

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$variable = 'ext_key';

ExtensionManagementUtility::addPageTSConfig(
    GeneralUtility::deprecationLog('foo')
);

?>

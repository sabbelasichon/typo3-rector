<?php

namespace Ssch\TYPO3Rector\Tests\Rector\CodeQuality\Rector\General\MoveExtensionManagementUtilityAddStaticFileIntoTCAOverridesRector\Fixture;

$variable = 'extension3';
$_EXTKEY = 'extension3';

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($variable, 'Configuration/TypoScript', 'Title');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('extension3', 'Configuration/OtherFolder', 'Title');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('doing' . $variable . 'weirdStuff', 'Configuration/TypoScript', 'Title');

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\CodeQuality\Rector\General\MoveExtensionManagementUtilityAddStaticFileIntoTCAOverridesRector\Fixture;

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
$variable = 'extension3';
$_EXTKEY = 'extension3';
ExtensionManagementUtility::addStaticFile('doing' . $variable . 'weirdStuff', 'Configuration/TypoScript', 'Title');

?>

<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use \TYPO3\CMS\Extbase\Utility\ExtensionUtility;

ExtensionUtility::configurePlugin();
ExtensionManagementUtility::addStaticFile('foo');

?>
-----
<?php

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin();
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('foo');

?>

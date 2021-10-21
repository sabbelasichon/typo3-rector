<?php

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin();
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('foo');

?>
-----
<?php

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
ExtensionUtility::configurePlugin();
ExtensionManagementUtility::addStaticFile('foo');

?>

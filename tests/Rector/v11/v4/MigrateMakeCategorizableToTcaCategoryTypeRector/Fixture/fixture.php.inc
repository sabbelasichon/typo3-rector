<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

ExtensionManagementUtility::makeCategorizable('my_extension', 'tx_myextension_table');

ExtensionManagementUtility::makeCategorizable('my_extension', 'tx_myextension_table', 'my_categories');

// This should remain unchanged
$someOtherCall = ExtensionManagementUtility::isLoaded('my_extension');

?>
-----
<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

$GLOBALS['TCA']['tx_myextension_table']['columns']['categories'] = ['config' => ['type' => 'category']];
ExtensionManagementUtility::addToAllTCAtypes('tx_myextension_table', 'categories');

$GLOBALS['TCA']['tx_myextension_table']['columns']['my_categories'] = ['config' => ['type' => 'category']];
ExtensionManagementUtility::addToAllTCAtypes('tx_myextension_table', 'my_categories');

// This should remain unchanged
$someOtherCall = ExtensionManagementUtility::isLoaded('my_extension');

?>

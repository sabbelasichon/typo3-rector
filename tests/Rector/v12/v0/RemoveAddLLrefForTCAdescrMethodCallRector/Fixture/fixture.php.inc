<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

ExtensionManagementUtility::addLLrefForTCAdescr('_MOD_web_info', 'EXT:info/Resources/Private/Language/locallang_csh_web_info.xlf');
ExtensionManagementUtility::addLLrefForTCAdescr('tt_content', 'EXT:frontend/Resources/Private/Language/locallang_csh_tt_content.xlf');

// This should remain unchanged
$someOtherCall = ExtensionManagementUtility::isLoaded('info');

?>
-----
<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

// This should remain unchanged
$someOtherCall = ExtensionManagementUtility::isLoaded('info');

?>

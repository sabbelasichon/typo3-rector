<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

GeneralUtility::makeInstanceService('serviceType', 'serviceSubType', 'key1, key2');
GeneralUtility::makeInstanceService('serviceType', 'serviceSubType', ['key1', 'key2']);
ExtensionManagementUtility::findService('serviceType', 'serviceSubType', 'key1, key2');
ExtensionManagementUtility::findService('serviceType', 'serviceSubType', ['key1', 'key2']);

?>
-----
<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

GeneralUtility::makeInstanceService('serviceType', 'serviceSubType', GeneralUtility::trimExplode(',', 'key1, key2', true));
GeneralUtility::makeInstanceService('serviceType', 'serviceSubType', ['key1', 'key2']);
ExtensionManagementUtility::findService('serviceType', 'serviceSubType', GeneralUtility::trimExplode(',', 'key1, key2', true));
ExtensionManagementUtility::findService('serviceType', 'serviceSubType', ['key1', 'key2']);

?>

<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;

GeneralUtility::logDeprecatedFunction();
GeneralUtility::logDeprecatedViewHelperAttribute();
GeneralUtility::deprecationLog('My Message');
GeneralUtility::getDeprecationLogFileName();

?>
-----
<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;

trigger_error('A useful message', E_USER_DEPRECATED);
trigger_error('A useful message', E_USER_DEPRECATED);
trigger_error('My Message', E_USER_DEPRECATED);

?>

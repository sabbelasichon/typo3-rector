<?php

use TYPO3\CMS\Core\Utility\PhpOptionsUtility;

PhpOptionsUtility::isSessionAutoStartEnabled();
PhpOptionsUtility::getIniValueBoolean('display_errors');

?>
-----
<?php

use TYPO3\CMS\Core\Utility\PhpOptionsUtility;

filter_var(ini_get('session.auto_start'), FILTER_VALIDATE_BOOLEAN, [FILTER_REQUIRE_SCALAR, FILTER_NULL_ON_FAILURE]);
filter_var(ini_get('display_errors'), FILTER_VALIDATE_BOOLEAN, [FILTER_REQUIRE_SCALAR, FILTER_NULL_ON_FAILURE]);

?>

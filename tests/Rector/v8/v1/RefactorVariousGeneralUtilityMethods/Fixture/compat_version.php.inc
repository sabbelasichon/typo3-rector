<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;

GeneralUtility::compat_version('foobar');

?>
-----
<?php

use TYPO3\CMS\Core\Utility\VersionNumberUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

VersionNumberUtility::convertVersionNumberToInteger(TYPO3_branch) >= VersionNumberUtility::convertVersionNumberToInteger('foobar');

?>

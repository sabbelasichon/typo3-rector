<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;

$maximum = GeneralUtility::getMaximumPathLength();

?>
-----
<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;

$maximum = PHP_MAXPATHLEN;

?>

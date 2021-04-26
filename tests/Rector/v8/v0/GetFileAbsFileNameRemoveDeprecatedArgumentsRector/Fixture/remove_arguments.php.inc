<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;

GeneralUtility::getFileAbsFileName('foo.txt', true, false);

?>
-----
<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;

GeneralUtility::getFileAbsFileName('foo.txt');

?>

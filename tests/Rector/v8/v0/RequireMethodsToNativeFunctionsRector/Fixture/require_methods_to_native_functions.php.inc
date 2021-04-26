<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;

GeneralUtility::requireOnce('somefile.php');
GeneralUtility::requireFile('some_other_file.php');

?>
-----
<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;

require_once 'somefile.php';
require 'some_other_file.php';

?>

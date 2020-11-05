<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;

$filename = 'somefile.php';
if(!GeneralUtility::verifyFilenameAgainstDenyPattern($filename)) {
}

if ($GLOBALS['TYPO3_CONF_VARS']['BE']['fileDenyPattern'] != FILE_DENY_PATTERN_DEFAULT)
{
}

?>
-----
<?php

use TYPO3\CMS\Core\Resource\Security\FileNameValidator;
use TYPO3\CMS\Core\Utility\GeneralUtility;
$filename = 'somefile.php';
if(!GeneralUtility::makeInstance(FileNameValidator::class)->isValid($filename)) {
}
if ($GLOBALS['TYPO3_CONF_VARS']['BE']['fileDenyPattern'] != FileNameValidator::DEFAULT_FILE_DENY_PATTERN)
{
}

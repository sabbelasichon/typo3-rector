<?php

$fileName = $GLOBALS['TSFE']->tmpl->getFileName('foo.text');

?>
-----
<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Resource\FilePathSanitizer;
$fileName = GeneralUtility::makeInstance(FilePathSanitizer::class)->sanitize((string) 'foo.text');

<?php

$fileName = $GLOBALS['TSFE']->tmpl->getFileName('foo.text');

?>
-----
<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Resource\FilePathSanitizer;
use TYPO3\CMS\Core\Resource\Exception\InvalidFileNameException;
use TYPO3\CMS\Core\Resource\Exception\InvalidPathException;
use TYPO3\CMS\Core\Resource\Exception\FileDoesNotExistException;
use TYPO3\CMS\Core\Resource\Exception\InvalidFileException;
use TYPO3\CMS\Core\TimeTracker\TimeTracker;
try {
    $fileName = GeneralUtility::makeInstance(FilePathSanitizer::class)->sanitize((string) 'foo.text');
} catch (InvalidFileNameException $e) {
    $fileName = null;
} catch (InvalidPathException|FileDoesNotExistException|InvalidFileException $e) {
    $fileName = null;
    if ($GLOBALS['TSFE']->tmpl->tt_track) {
        GeneralUtility::makeInstance(TimeTracker::class)->setTSlogMessage($e->getMessage(), 3);
    }
}

<?php

use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$someObject = GeneralUtility::makeInstance(BackendUserAuthentication::class);
$someObject->simplelog('Message 1', 'extKey1', 456);
$someObject->simplelog('Message 2', 'extKey2');
$someObject->simplelog('Message 3', '');

?>
-----
<?php

use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$someObject = GeneralUtility::makeInstance(BackendUserAuthentication::class);
$someObject->writelog(4, 0, 456, '[extKey1] Message 1', []);
$someObject->writelog(4, 0, 0, '[extKey2] Message 2', []);
$someObject->writelog(4, 0, 0, 'Message 3', []);

?>

<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v9\v0\SubstituteGeneralUtilityDevLogRector\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;

$data = ['data'];
GeneralUtility::devLog('message', 'foo', 0, $data);
GeneralUtility::devLog('message', 'foo', 1, $data);
GeneralUtility::devLog('message', 'foo', 2, $data);
GeneralUtility::devLog('message', 'foo', GeneralUtility::SYSLOG_SEVERITY_ERROR, $data);
GeneralUtility::devLog('message', 'foo', 4, $data);

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v9\v0\SubstituteGeneralUtilityDevLogRector\Fixture;

use TYPO3\CMS\Core\Log\LogManager;
use TYPO3\CMS\Core\Log\LogLevel;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$data = ['data'];
GeneralUtility::makeInstance(LogManager::class)->getLogger(__CLASS__)->log(LogLevel::INFO, 'message', $data);
GeneralUtility::makeInstance(LogManager::class)->getLogger(__CLASS__)->log(LogLevel::NOTICE, 'message', $data);
GeneralUtility::makeInstance(LogManager::class)->getLogger(__CLASS__)->log(LogLevel::WARNING, 'message', $data);
GeneralUtility::makeInstance(LogManager::class)->getLogger(__CLASS__)->log(LogLevel::ERROR, 'message', $data);
GeneralUtility::makeInstance(LogManager::class)->getLogger(__CLASS__)->log(LogLevel::CRITICAL, 'message', $data);

?>

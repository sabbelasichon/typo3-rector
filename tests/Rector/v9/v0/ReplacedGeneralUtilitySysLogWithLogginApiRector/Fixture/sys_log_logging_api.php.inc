<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v9\v0\ReplacedGeneralUtilitySysLogWithLogginApiRector\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;

GeneralUtility::initSysLog();
GeneralUtility::sysLog('message', 'foo');
GeneralUtility::sysLog('message', 'foo', 0);
GeneralUtility::sysLog('message', 'foo', 1);
GeneralUtility::sysLog('message', 'foo', 2);
GeneralUtility::sysLog('message', 'foo', '3');
GeneralUtility::sysLog('message', 'foo', 4);

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v9\v0\ReplacedGeneralUtilitySysLogWithLogginApiRector\Fixture;

use TYPO3\CMS\Core\Log\LogManager;
use TYPO3\CMS\Core\Log\LogLevel;
use TYPO3\CMS\Core\Utility\GeneralUtility;
GeneralUtility::makeInstance(LogManager::class)->getLogger(__CLASS__)->log(LogLevel::INFO, 'message');
GeneralUtility::makeInstance(LogManager::class)->getLogger(__CLASS__)->log(LogLevel::INFO, 'message');
GeneralUtility::makeInstance(LogManager::class)->getLogger(__CLASS__)->log(LogLevel::NOTICE, 'message');
GeneralUtility::makeInstance(LogManager::class)->getLogger(__CLASS__)->log(LogLevel::WARNING, 'message');
GeneralUtility::makeInstance(LogManager::class)->getLogger(__CLASS__)->log(LogLevel::ERROR, 'message');
GeneralUtility::makeInstance(LogManager::class)->getLogger(__CLASS__)->log(LogLevel::CRITICAL, 'message');

?>

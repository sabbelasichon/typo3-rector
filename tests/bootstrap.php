<?php

declare(strict_types=1);

use Ssch\TYPO3Rector\Stubs\StubLoader;
use TYPO3\CMS\Core\Resource\Security\FileNameValidator;
use TYPO3\CMS\Core\TimeTracker\TimeTracker;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

require_once __DIR__ . '/../vendor/autoload.php';

// silent deprecations, since we test them
error_reporting(E_ALL ^ E_DEPRECATED);

// performance boost
gc_disable();

// load stubs
$stubLoader = new StubLoader();
$stubLoader->loadStubs();

$GLOBALS['TSFE'] = new TypoScriptFrontendController();
$GLOBALS['TT'] = new TimeTracker();
$GLOBALS['TYPO3_LOADED_EXT'] = [];
$GLOBALS['PARSETIME_START'] = time();
$GLOBALS['TYPO3_MISC']['microtime_start'] = microtime();
$GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['dbname'] = 'dbname';
$GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['user'] = 'user';
$GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['password'] = 'password';
$GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['host'] = 'host';
$GLOBALS['TYPO3_CONF_VARS']['BE']['fileDenyPattern'] = FileNameValidator::DEFAULT_FILE_DENY_PATTERN;

define('FILE_DENY_PATTERN_DEFAULT', 'some');
define('PATH_site', getcwd());
define('TYPO3_db', 'TYPO3_db');
define('TYPO3_db_username', 'TYPO3_db_username');
define('TYPO3_db_password', 'TYPO3_db_password');
define('TYPO3_db_host', 'TYPO3_db_host');

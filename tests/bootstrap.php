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

define('TYPO3_URL_MAILINGLISTS', 'http://lists.typo3.org/cgi-bin/mailman/listinfo');
define('TYPO3_URL_DOCUMENTATION', 'https://typo3.org/documentation/');
define('TYPO3_URL_DOCUMENTATION_TSREF', 'https://docs.typo3.org/typo3cms/TyposcriptReference/');
define('TYPO3_URL_DOCUMENTATION_TSCONFIG', 'https://docs.typo3.org/typo3cms/TSconfigReference/');
define('TYPO3_URL_CONSULTANCY', 'https://typo3.org/support/professional-services/');
define('TYPO3_URL_CONTRIBUTE', 'https://typo3.org/contribute/');
define('TYPO3_URL_SECURITY', 'https://typo3.org/teams/security/');
define('TYPO3_URL_DOWNLOAD', 'https://typo3.org/download/');
define('TYPO3_URL_SYSTEMREQUIREMENTS', 'https://typo3.org/typo3-cms/overview/requirements/');
defined('NUL') ?: define('NUL', "\0");
defined('TAB') ?: define('TAB', "\t");
defined('SUB') ?: define('SUB', chr(26));
define('T3_ERR_SV_GENERAL', -1);
define('T3_ERR_SV_NOT_AVAIL', -2);
define('T3_ERR_SV_WRONG_SUBTYPE', -3);
define('T3_ERR_SV_NO_INPUT', -4);
define('T3_ERR_SV_FILE_NOT_FOUND', -20);
define('T3_ERR_SV_FILE_READ', -21);
define('T3_ERR_SV_FILE_WRITE', -22);
define('T3_ERR_SV_PROG_NOT_FOUND', -40);
define('T3_ERR_SV_PROG_FAILED', -41);

define('FILE_DENY_PATTERN_DEFAULT', 'some');
define('PATH_site', getcwd());
define('TYPO3_db', 'TYPO3_db');
define('TYPO3_db_username', 'TYPO3_db_username');
define('TYPO3_db_password', 'TYPO3_db_password');
define('TYPO3_db_host', 'TYPO3_db_host');

define('TYPO3_version', '9.5.21');
define('TYPO3_branch', '9.5');

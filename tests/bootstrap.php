<?php

declare(strict_types=1);

use Ssch\TYPO3Rector\Stubs\StubLoader;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Resource\Security\FileNameValidator;
use TYPO3\CMS\Core\TimeTracker\TimeTracker;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

require_once __DIR__ . '/../vendor/autoload.php';

// silent deprecations, since we test them
error_reporting(E_ALL & ~E_NOTICE | E_DEPRECATED);

// performance boost
gc_disable();

// load stubs
$stubLoader = new StubLoader();
$stubLoader->loadStubs();

// define some globals
$GLOBALS['TSFE'] = new TypoScriptFrontendController();
$GLOBALS['TT'] = new TimeTracker();
$GLOBALS['BE_USER'] = new BackendUserAuthentication();
$GLOBALS['LANG'] = new LanguageService();
$GLOBALS['TYPO3_LOADED_EXT'] = [];
$GLOBALS['PARSETIME_START'] = time();
$GLOBALS['TYPO3_MISC']['microtime_start'] = microtime();
$GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['dbname'] = 'dbname';
$GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['user'] = 'user';
$GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['password'] = 'password';
$GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['host'] = 'host';
$GLOBALS['TYPO3_CONF_VARS']['BE']['fileDenyPattern'] = FileNameValidator::DEFAULT_FILE_DENY_PATTERN;
$GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['foo'] = 'a:6:{s:9:"loginLogo";s:8:"logo.jpg";s:19:"loginHighlightColor";s:7:"#000000";s:20:"loginBackgroundImage";s:8:"logo.jpg";s:13:"loginFootnote";s:8:"Footnote";s:11:"backendLogo";s:0:"";s:14:"backendFavicon";s:0:"";}';

// Define some constants
defined('TYPO3_MODE') ?: define('TYPO3_MODE', 'BE');
defined('NUL') ?: define('NUL', "\0");
defined('TAB') ?: define('TAB', "\t");
defined('SUB') ?: define('SUB', chr(26));
defined('LF') ?: define('LF', chr(10));
defined('TYPO3_URL_MAILINGLISTS') ?: define(
    'TYPO3_URL_MAILINGLISTS',
    'http://lists.typo3.org/cgi-bin/mailman/listinfo'
);
defined('TYPO3_URL_DOCUMENTATION') ?: define('TYPO3_URL_DOCUMENTATION', 'https://typo3.org/documentation/');
defined('TYPO3_URL_DOCUMENTATION_TSREF') ?: define(
    'TYPO3_URL_DOCUMENTATION_TSREF',
    'https://docs.typo3.org/typo3cms/TyposcriptReference/'
);
defined('TYPO3_URL_DOCUMENTATION_TSCONFIG') ?: define(
    'TYPO3_URL_DOCUMENTATION_TSCONFIG',
    'https://docs.typo3.org/typo3cms/TSconfigReference/'
);
defined('TYPO3_URL_CONSULTANCY') ?: define('TYPO3_URL_CONSULTANCY', 'https://typo3.org/support/professional-services/');
defined('TYPO3_URL_CONTRIBUTE') ?: define('TYPO3_URL_CONTRIBUTE', 'https://typo3.org/contribute/');
defined('TYPO3_URL_SECURITY') ?: define('TYPO3_URL_SECURITY', 'https://typo3.org/teams/security/');
defined('TYPO3_URL_DOWNLOAD') ?: define('TYPO3_URL_DOWNLOAD', 'https://typo3.org/download/');
defined('TYPO3_URL_SYSTEMREQUIREMENTS') ?: define(
    'TYPO3_URL_SYSTEMREQUIREMENTS',
    'https://typo3.org/typo3-cms/overview/requirements/'
);
defined('T3_ERR_SV_GENERAL') ?: define('T3_ERR_SV_GENERAL', -1);
defined('T3_ERR_SV_NOT_AVAIL') ?: define('T3_ERR_SV_NOT_AVAIL', -2);
defined('T3_ERR_SV_WRONG_SUBTYPE') ?: define('T3_ERR_SV_WRONG_SUBTYPE', -3);
defined('T3_ERR_SV_NO_INPUT') ?: define('T3_ERR_SV_NO_INPUT', -4);
defined('T3_ERR_SV_FILE_NOT_FOUND') ?: define('T3_ERR_SV_FILE_NOT_FOUND', -20);
defined('T3_ERR_SV_FILE_READ') ?: define('T3_ERR_SV_FILE_READ', -21);
defined('T3_ERR_SV_FILE_WRITE') ?: define('T3_ERR_SV_FILE_WRITE', -22);
defined('T3_ERR_SV_PROG_NOT_FOUND') ?: define('T3_ERR_SV_PROG_NOT_FOUND', -40);
defined('T3_ERR_SV_PROG_FAILED') ?: define('T3_ERR_SV_PROG_FAILED', -41);
defined('FILE_DENY_PATTERN_DEFAULT') ?: define('FILE_DENY_PATTERN_DEFAULT', 'some');
defined('PATH_site') ?: define('PATH_site', getcwd());
defined('TYPO3_db') ?: define('TYPO3_db', 'TYPO3_db');
defined('TYPO3_db_username') ?: define('TYPO3_db_username', 'TYPO3_db_username');
defined('TYPO3_db_password') ?: define('TYPO3_db_password', 'TYPO3_db_password');
defined('TYPO3_db_host') ?: define('TYPO3_db_host', 'TYPO3_db_host');
defined('TYPO3_version') ?: define('TYPO3_version', '9.5.21');
defined('TYPO3_copyright_year') ?: define('TYPO3_copyright_year', 'foo');
defined('TYPO3_branch') ?: define('TYPO3_branch', '9.5');
defined('TYPO3_URL_GENERAL') ?: define('TYPO3_URL_GENERAL', 'https://typo3.org/');
defined('TYPO3_URL_LICENSE') ?: define('TYPO3_URL_LICENSE', 'https://typo3.org/typo3-cms/overview/licenses/');
defined('TYPO3_URL_EXCEPTION') ?: define('TYPO3_URL_EXCEPTION', 'https://typo3.org/go/exception/CMS/');
defined('TYPO3_URL_DONATE') ?: define('TYPO3_URL_DONATE', 'https://typo3.org/community/contribute/donate/');
defined('TYPO3_URL_WIKI_OPCODECACHE') ?: define('TYPO3_URL_WIKI_OPCODECACHE', 'https://wiki.typo3.orgOpcode_Cache/');
defined('TYPO3_REQUESTTYPE_FE') ?: define('TYPO3_REQUESTTYPE_FE', 1);
defined('TYPO3_REQUESTTYPE_BE') ?: define('TYPO3_REQUESTTYPE_BE', 2);
defined('TYPO3_REQUESTTYPE_CLI') ?: define('TYPO3_REQUESTTYPE_CLI', 4);
defined('TYPO3_REQUESTTYPE_AJAX') ?: define('TYPO3_REQUESTTYPE_AJAX', 8);
defined('TYPO3_REQUESTTYPE_INSTALL') ?: define('TYPO3_REQUESTTYPE_INSTALL', 16);
defined('PATH_thisScript') ?: define('PATH_thisScript', 'foo');
defined('TYPO3_OS') ?: define('TYPO3_OS', 'foo');
defined('PATH_typo3conf') ?: define('PATH_typo3conf', 'foo');
defined('PATH_typo3') ?: define('PATH_typo3', 'foo');
defined('TYPO3_REQUESTTYPE') ?: define('TYPO3_REQUESTTYPE', 'foo');
defined('TYPO3_COMPOSER_MODE') ?: define('TYPO3_COMPOSER_MODE', 'foo');
defined('TYPO3_mainDir') ?: define('TYPO3_mainDir', 'foo');

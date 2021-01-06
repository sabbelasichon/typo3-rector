<?php

declare(strict_types=1);

defined('LF') ?: define('LF', chr(10));
defined('CR') ?: define('CR', chr(13));
defined('CRLF') ?: define('CRLF', CR . LF);
defined('NUL') ?: define('NUL', "\0");
defined('TAB') ?: define('TAB', "\t");
defined('SUB') ?: define('SUB', chr(26));

defined('T3_ERR_SV_GENERAL') ?: define('T3_ERR_SV_GENERAL', -1);
defined('T3_ERR_SV_NOT_AVAIL') ?: define('T3_ERR_SV_NOT_AVAIL', -2);
defined('T3_ERR_SV_WRONG_SUBTYPE') ?: define('T3_ERR_SV_WRONG_SUBTYPE', -3);
defined('T3_ERR_SV_NO_INPUT') ?: define('T3_ERR_SV_NO_INPUT', -4);
defined('T3_ERR_SV_FILE_NOT_FOUND') ?: define('T3_ERR_SV_FILE_NOT_FOUND', -20);
defined('T3_ERR_SV_FILE_READ') ?: define('T3_ERR_SV_FILE_READ', -21);
defined('T3_ERR_SV_FILE_WRITE') ?: define('T3_ERR_SV_FILE_WRITE', -22);
defined('T3_ERR_SV_PROG_NOT_FOUND') ?: define('T3_ERR_SV_PROG_NOT_FOUND', -40);
defined('T3_ERR_SV_PROG_FAILED') ?: define('T3_ERR_SV_PROG_FAILED', -41);

define('FILE_DENY_PATTERN_DEFAULT', '\\.(php[3-8]?|phpsh|phtml|pht|phar|shtml|cgi)(\\..*)?$|\\.pl$|^\\.htaccess$');

define('TYPO3_REQUESTTYPE', 0);
define('TYPO3_REQUESTTYPE_FE', 1);
define('TYPO3_REQUESTTYPE_BE', 2);
define('TYPO3_REQUESTTYPE_CLI', 4);
define('TYPO3_REQUESTTYPE_AJAX', 8);
define('TYPO3_REQUESTTYPE_INSTALL', 16);

define('TYPO3', true);
define('TYPO3_MODE', '');
define('TYPO3_mainDir', 'typo3/');
define('TYPO3_version', '');
define('TYPO3_branch', '');

define('PATH_site', getcwd());
define('PATH_thisScript', '');
define('TYPO3_OS', '');
define('PATH_typo3conf', '');
define('PATH_typo3', '');
defined('TYPO3_COMPOSER_MODE') ?: define('TYPO3_COMPOSER_MODE', false);

define('TYPO3_URL_MAILINGLISTS', 'http://lists.typo3.org/cgi-bin/mailman/listinfo');
define('TYPO3_URL_DOCUMENTATION', 'https://typo3.org/documentation/');
define('TYPO3_URL_DOCUMENTATION_TSREF', 'https://docs.typo3.org/typo3cms/TyposcriptReference/');
define('TYPO3_URL_DOCUMENTATION_TSCONFIG', 'https://docs.typo3.org/typo3cms/TSconfigReference/');
define('TYPO3_URL_CONSULTANCY', 'https://typo3.org/support/professional-services/');
define('TYPO3_URL_CONTRIBUTE', 'https://typo3.org/contribute/');
define('TYPO3_URL_SECURITY', 'https://typo3.org/teams/security/');
define('TYPO3_URL_DOWNLOAD', 'https://typo3.org/download/');
define('TYPO3_URL_SYSTEMREQUIREMENTS', 'https://typo3.org/typo3-cms/overview/requirements/');

define('TYPO3_db', 'TYPO3_db');
define('TYPO3_db_username', 'TYPO3_db_username');
define('TYPO3_db_password', 'TYPO3_db_password');
define('TYPO3_db_host', 'TYPO3_db_host');
define('TYPO3_copyright_year', '');
define('TYPO3_URL_GENERAL', 'https://typo3.org/');
define('TYPO3_URL_LICENSE', 'https://typo3.org/typo3-cms/overview/licenses/');
define('TYPO3_URL_EXCEPTION', 'https://typo3.org/go/exception/CMS/');
define('TYPO3_URL_DONATE', 'https://typo3.org/community/contribute/donate/');
define('TYPO3_URL_WIKI_OPCODECACHE', 'https://wiki.typo3.orgOpcode_Cache/');

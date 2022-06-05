<?php

declare(strict_types=1);

use Tracy\Debugger;

require_once __DIR__ . '/../vendor/autoload.php';
include_once __DIR__ . '/../typo3.constants.php';
// silent deprecations, since we test them
error_reporting(E_ALL & ~E_NOTICE | E_DEPRECATED);
// performance boost
gc_disable();
// define some globals

$GLOBALS['TYPO3_LOADED_EXT'] = [];
$GLOBALS['PARSETIME_START'] = time();
$GLOBALS['TYPO3_MISC']['microtime_start'] = microtime();
$GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'] = 'jpg, gif';
$GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['dbname'] = 'dbname';
$GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['user'] = 'user';
$GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['password'] = 'password';
$GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['host'] = 'host';
$GLOBALS['TYPO3_CONF_VARS']['BE']['fileDenyPattern'] = '\\.(php[3-8]?|phpsh|phtml|pht|phar|shtml|cgi)(\\..*)?$|\\.pl$|^\\.htaccess$';
$GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['foo'] = 'a:6:{s:9:"loginLogo";s:8:"logo.jpg";s:19:"loginHighlightColor";s:7:"#000000";s:20:"loginBackgroundImage";s:8:"logo.jpg";s:13:"loginFootnote";s:8:"Footnote";s:11:"backendLogo";s:0:"";s:14:"backendFavicon";s:0:"";}';
// for dump() function
Debugger::$maxDepth = 2;


// autoload rector php-parser first with local paths
// build preload file to autoload local php-parser instead of phpstan one, e.g. in case of early upgrade
exec('php vendor/rector/rector-src/build/build-preload.php .');
sleep(1);

require __DIR__ . '/../preload.php';
unlink(__DIR__ . '/../preload.php');

<?php

declare(strict_types=1);

use Ssch\TYPO3Rector\Stubs\StubLoader;
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

define('PATH_site', getcwd());

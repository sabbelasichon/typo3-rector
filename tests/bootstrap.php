<?php

declare(strict_types=1);

use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

require_once __DIR__ . '/../vendor/autoload.php';

$GLOBALS['TSFE'] = new TypoScriptFrontendController();

// silent deprecations, since we test them
error_reporting(E_ALL ^ E_DEPRECATED);

// performance boost
gc_disable();

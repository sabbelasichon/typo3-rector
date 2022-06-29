#!/usr/bin/env php
<?php

use Ssch\TYPO3Rector\Console\Application\Typo3RectorConsoleApplication;
use Ssch\TYPO3Rector\Console\Application\Typo3RectorKernel;

include_once __DIR__ . '/../vendor/autoload.php';

$kernel = new Typo3RectorKernel('dev', true);
$kernel->boot();
$container = $kernel->getContainer();

$application = $container->get(Typo3RectorConsoleApplication::class);

if ($application instanceof Typo3RectorConsoleApplication) {
    exit($application->run());
}

#!/usr/bin/env php
<?php

use Ssch\TYPO3Rector\Console\Application\Typo3RectorConsoleApplication;
use Ssch\TYPO3Rector\Console\Application\Typo3RectorKernel;
use Ssch\TYPO3Rector\Generator\Command\Typo3GenerateCommand;

include_once __DIR__ . '/../vendor/autoload.php';

$kernel = new Typo3RectorKernel('dev', true);
$kernel->boot();
$container = $kernel->getContainer();

$application = $container->get(Typo3RectorConsoleApplication::class);

if ($application instanceof Typo3RectorConsoleApplication) {
    $application->setDefaultCommand((string) Typo3GenerateCommand::getDefaultName());
    $application->run();
}

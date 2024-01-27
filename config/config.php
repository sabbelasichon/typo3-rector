<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\Configuration\Typo3Option;
use Ssch\TYPO3Rector\Filesystem\FileInfoFactory;
use Ssch\TYPO3Rector\Filesystem\FilesFinder;
use Ssch\TYPO3Rector\Helper\OldSeverityToLogLevelMapper;
use Ssch\TYPO3Rector\NodeAnalyzer\ClassConstAnalyzer;
use Ssch\TYPO3Rector\NodeAnalyzer\ExtbaseControllerRedirectAnalyzer;
use Ssch\TYPO3Rector\NodeFactory\InjectMethodFactory;
use Ssch\TYPO3Rector\NodeFactory\Typo3GlobalsFactory;
use Ssch\TYPO3Rector\NodeResolver\Typo3NodeResolver;
use Ssch\TYPO3Rector\Yaml\SymfonyYamlParser;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->importNames();
    $rectorConfig->phpstanConfig(Typo3Option::PHPSTAN_FOR_RECTOR_PATH);
    // this will not import root namespace classes, like \DateTime or \Exception
    $rectorConfig->importShortClasses(false);

    $rectorConfig->singleton(FileInfoFactory::class);
    $rectorConfig->singleton(FilesFinder::class);
    $rectorConfig->singleton(OldSeverityToLogLevelMapper::class);
    $rectorConfig->singleton(ClassConstAnalyzer::class);
    $rectorConfig->singleton(ExtbaseControllerRedirectAnalyzer::class);
    $rectorConfig->singleton(InjectMethodFactory::class);
    $rectorConfig->singleton(Typo3GlobalsFactory::class);
    $rectorConfig->singleton(Typo3NodeResolver::class);
    $rectorConfig->singleton(SymfonyYamlParser::class);
};

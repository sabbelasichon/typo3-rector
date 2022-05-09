<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\FileProcessor\TypoScript\Conditions\ApplicationContextConditionMatcher;
use Ssch\TYPO3Rector\FileProcessor\TypoScript\Conditions\BrowserConditionMatcher;
use Ssch\TYPO3Rector\FileProcessor\TypoScript\Conditions\CompatVersionConditionMatcher;
use Ssch\TYPO3Rector\FileProcessor\TypoScript\Conditions\GlobalStringConditionMatcher;
use Ssch\TYPO3Rector\FileProcessor\TypoScript\Conditions\GlobalVarConditionMatcher;
use Ssch\TYPO3Rector\FileProcessor\TypoScript\Conditions\HostnameConditionMatcher;
use Ssch\TYPO3Rector\FileProcessor\TypoScript\Conditions\IPConditionMatcher;
use Ssch\TYPO3Rector\FileProcessor\TypoScript\Conditions\LanguageConditionMatcher;
use Ssch\TYPO3Rector\FileProcessor\TypoScript\Conditions\LoginUserConditionMatcher;
use Ssch\TYPO3Rector\FileProcessor\TypoScript\Conditions\PageConditionMatcher;
use Ssch\TYPO3Rector\FileProcessor\TypoScript\Conditions\PIDinRootlineConditionMatcher;
use Ssch\TYPO3Rector\FileProcessor\TypoScript\Conditions\TimeConditionMatcher;
use Ssch\TYPO3Rector\FileProcessor\TypoScript\Conditions\TreeLevelConditionMatcher;
use Ssch\TYPO3Rector\FileProcessor\TypoScript\Conditions\UsergroupConditionMatcherMatcher;
use Ssch\TYPO3Rector\FileProcessor\TypoScript\Conditions\VersionConditionMatcher;
use Ssch\TYPO3Rector\FileProcessor\TypoScript\PostRector\LibFluidContentToContentElementTypoScriptPostRector;
use Ssch\TYPO3Rector\FileProcessor\TypoScript\Rector\AdditionalHeadersToArrayTypoScriptRector;
use Ssch\TYPO3Rector\FileProcessor\TypoScript\Rector\ExtbasePersistenceTypoScriptRector;
use Ssch\TYPO3Rector\FileProcessor\TypoScript\Rector\FileIncludeToImportStatementTypoScriptRector;
use Ssch\TYPO3Rector\FileProcessor\TypoScript\Rector\LibFluidContentToLibContentElementRector;
use Ssch\TYPO3Rector\FileProcessor\TypoScript\Rector\OldConditionToExpressionLanguageTypoScriptRector;
use Ssch\TYPO3Rector\FileProcessor\TypoScript\Rector\TemplateToFluidTemplateTypoScriptRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../../../config/config_test.php');

    $services = $rectorConfig->services();
    $services->set(ApplicationContextConditionMatcher::class);
    $services->set(BrowserConditionMatcher::class);
    $services->set(CompatVersionConditionMatcher::class);
    $services->set(GlobalStringConditionMatcher::class);
    $services->set(GlobalVarConditionMatcher::class);
    $services->set(HostnameConditionMatcher::class);
    $services->set(IPConditionMatcher::class);
    $services->set(LanguageConditionMatcher::class);
    $services->set(LoginUserConditionMatcher::class);
    $services->set(PageConditionMatcher::class);
    $services->set(PIDinRootlineConditionMatcher::class);
    $services->set(TimeConditionMatcher::class);
    $services->set(TreeLevelConditionMatcher::class);
    $services->set(UsergroupConditionMatcherMatcher::class);
    $services->set(VersionConditionMatcher::class);

    $rectorConfig->rule(OldConditionToExpressionLanguageTypoScriptRector::class);
    $rectorConfig->rule(FileIncludeToImportStatementTypoScriptRector::class);
    $rectorConfig->rule(ExtbasePersistenceTypoScriptRector::class);
    $rectorConfig->rule(AdditionalHeadersToArrayTypoScriptRector::class);
    $rectorConfig->rule(LibFluidContentToLibContentElementRector::class);
    $rectorConfig->rule(LibFluidContentToContentElementTypoScriptPostRector::class);
    $rectorConfig->rule(TemplateToFluidTemplateTypoScriptRector::class);
};

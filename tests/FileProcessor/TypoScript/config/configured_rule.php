<?php

declare(strict_types=1);

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
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/../../../../config/config_test.php');
    $services = $containerConfigurator->services();
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
    $services->set(OldConditionToExpressionLanguageTypoScriptRector::class);
    $services->set(FileIncludeToImportStatementTypoScriptRector::class);
    $services->set(ExtbasePersistenceTypoScriptRector::class);
    $services->set(AdditionalHeadersToArrayTypoScriptRector::class);
    $services->set(LibFluidContentToLibContentElementRector::class);
    $services->set(LibFluidContentToContentElementTypoScriptPostRector::class);
};

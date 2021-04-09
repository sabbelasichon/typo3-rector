<?php

declare(strict_types=1);

use Ssch\TYPO3Rector\TypoScript\Conditions\ApplicationContextConditionMatcher;
use Ssch\TYPO3Rector\TypoScript\Conditions\BrowserConditionMatcher;
use Ssch\TYPO3Rector\TypoScript\Conditions\CompatVersionConditionMatcher;
use Ssch\TYPO3Rector\TypoScript\Conditions\GlobalStringConditionMatcher;
use Ssch\TYPO3Rector\TypoScript\Conditions\GlobalVarConditionMatcher;
use Ssch\TYPO3Rector\TypoScript\Conditions\HostnameConditionMatcher;
use Ssch\TYPO3Rector\TypoScript\Conditions\IPConditionMatcher;
use Ssch\TYPO3Rector\TypoScript\Conditions\LanguageConditionMatcher;
use Ssch\TYPO3Rector\TypoScript\Conditions\LoginUserConditionMatcher;
use Ssch\TYPO3Rector\TypoScript\Conditions\PageConditionMatcher;
use Ssch\TYPO3Rector\TypoScript\Conditions\PIDinRootlineConditionMatcher;
use Ssch\TYPO3Rector\TypoScript\Conditions\TimeConditionMatcher;
use Ssch\TYPO3Rector\TypoScript\Conditions\TreeLevelConditionMatcher;
use Ssch\TYPO3Rector\TypoScript\Conditions\UsergroupConditionMatcherMatcher;
use Ssch\TYPO3Rector\TypoScript\Conditions\VersionConditionMatcher;
use Ssch\TYPO3Rector\TypoScript\Visitors\FileIncludeToImportStatementVisitor;
use Ssch\TYPO3Rector\TypoScript\Visitors\OldConditionToExpressionLanguageVisitor;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/../../../config/services.php');
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
    $services->set(OldConditionToExpressionLanguageVisitor::class);
    $services->set(FileIncludeToImportStatementVisitor::class);
};

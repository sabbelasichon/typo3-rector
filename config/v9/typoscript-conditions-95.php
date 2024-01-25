<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\FileProcessor\TypoScript\Conditions\AdminUserConditionMatcher;
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

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');

    $services = $rectorConfig->services();
    $services->set(ApplicationContextConditionMatcher::class)->tag('typo3_rector.typoscript_condition_matcher');
    $services->set(BrowserConditionMatcher::class)->tag('typo3_rector.typoscript_condition_matcher');
    $services->set(CompatVersionConditionMatcher::class)->tag('typo3_rector.typoscript_condition_matcher');
    $services->set(GlobalStringConditionMatcher::class)->tag('typo3_rector.typoscript_condition_matcher');
    $services->set(GlobalVarConditionMatcher::class)->tag('typo3_rector.typoscript_condition_matcher');
    $services->set(HostnameConditionMatcher::class)->tag('typo3_rector.typoscript_condition_matcher');
    $services->set(IPConditionMatcher::class)->tag('typo3_rector.typoscript_condition_matcher');
    $services->set(LanguageConditionMatcher::class)->tag('typo3_rector.typoscript_condition_matcher');
    $services->set(LoginUserConditionMatcher::class)->tag('typo3_rector.typoscript_condition_matcher');
    $services->set(PageConditionMatcher::class)->tag('typo3_rector.typoscript_condition_matcher');
    $services->set(PIDinRootlineConditionMatcher::class)->tag('typo3_rector.typoscript_condition_matcher');
    $services->set(TimeConditionMatcher::class)->tag('typo3_rector.typoscript_condition_matcher');
    $services->set(TreeLevelConditionMatcher::class)->tag('typo3_rector.typoscript_condition_matcher');
    $services->set(UsergroupConditionMatcherMatcher::class)->tag('typo3_rector.typoscript_condition_matcher');
    $services->set(VersionConditionMatcher::class)->tag('typo3_rector.typoscript_condition_matcher');
    $services->set(AdminUserConditionMatcher::class)->tag('typo3_rector.typoscript_condition_matcher');
};

<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\Configuration\Typo3Option;
use Ssch\TYPO3Rector\FileProcessor\TypoScript\PostRector\v8\v7\LibFluidContentToContentElementTypoScriptPostRector;
use Ssch\TYPO3Rector\FileProcessor\TypoScript\Rector\v10\v0\ExtbasePersistenceTypoScriptRector;
use Ssch\TYPO3Rector\FileProcessor\TypoScript\Rector\v11\v0\TemplateToFluidTemplateTypoScriptRector;
use Ssch\TYPO3Rector\FileProcessor\TypoScript\Rector\v7\v1\AdditionalHeadersToArrayTypoScriptRector;
use Ssch\TYPO3Rector\FileProcessor\TypoScript\Rector\v8\v7\LibFluidContentToLibContentElementRector;
use Ssch\TYPO3Rector\FileProcessor\TypoScript\Rector\v9\v0\FileIncludeToImportStatementTypoScriptRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../../../config/config_test.php');
    $rectorConfig->import(__DIR__ . '/../../../../config/v9/typoscript-conditions-95.php');
    $rectorConfig->import(__DIR__ . '/../../../../config/v10/typoscript-conditions-104.php');

    $parameters = $rectorConfig->parameters();

    $parameters->set(Typo3Option::TYPOSCRIPT_INDENT_CONDITIONS, true);

    $rectorConfig->services()
        ->set(AdditionalHeadersToArrayTypoScriptRector::class)->tag('typo3_rector.typoscript_rectors');
    $rectorConfig->services()
        ->set(LibFluidContentToLibContentElementRector::class)->tag('typo3_rector.typoscript_rectors');
    $rectorConfig->services()
        ->set(LibFluidContentToContentElementTypoScriptPostRector::class)->tag('typo3_rector.typoscript_post_rectors');
    $rectorConfig->services()
        ->set(FileIncludeToImportStatementTypoScriptRector::class)->tag('typo3_rector.typoscript_rectors');
    $rectorConfig->services()
        ->set(ExtbasePersistenceTypoScriptRector::class)->tag('typo3_rector.typoscript_rectors');
    $rectorConfig->services()
        ->set(TemplateToFluidTemplateTypoScriptRector::class)->tag('typo3_rector.typoscript_rectors');
};

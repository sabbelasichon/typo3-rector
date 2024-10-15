<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\CodeQuality\General\MethodGetInstanceToMakeInstanceCallRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../../../../../config/config_test.php');
    $rectorConfig
        ->ruleWithConfiguration(MethodGetInstanceToMakeInstanceCallRector::class, [
            'TYPO3\CMS\Core\Resource\Index\ExtractorRegistry',
            'TYPO3\CMS\Core\Resource\Index\FileIndexRepository',
            'TYPO3\CMS\Core\Resource\Index\MetaDataRepository',
            'TYPO3\CMS\Core\Resource\OnlineMedia\Helpers\OnlineMediaHelperRegistry',
            'TYPO3\CMS\Core\Resource\Rendering\RendererRegistry',
            'TYPO3\CMS\Core\Resource\TextExtraction\TextExtractorRegistry',
            'TYPO3\CMS\Core\Utility\GeneralUtility',
            'TYPO3\CMS\Form\Service\TranslationService',
            'TYPO3\CMS\T3editor\Registry\AddonRegistry',
            'TYPO3\CMS\T3editor\Registry\ModeRegistry',
        ]);
};

<?php

declare(strict_types=1);

use Ssch\TYPO3Rector\Rector\General\MethodGetInstanceToMakeInstanceCallRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/../../../../../config/config_test.php');

    $services = $containerConfigurator->services();
    $services->set(MethodGetInstanceToMakeInstanceCallRector::class)
        ->configure([
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

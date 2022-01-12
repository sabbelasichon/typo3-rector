<?php

declare(strict_types=1);

use Rector\Renaming\Rector\StaticCall\RenameStaticMethodRector;
use Rector\Renaming\ValueObject\RenameStaticMethod;
use Ssch\TYPO3Rector\Rector\General\MethodGetInstanceToMakeInstanceCallRector;
use Ssch\TYPO3Rector\Rector\v11\v5\FlexFormToolsArrayValueByPathRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/../config.php');
    $services = $containerConfigurator->services();
    $services->set(FlexFormToolsArrayValueByPathRector::class);
    $services->set(RenameStaticMethodRector::class)
        ->configure([
            new RenameStaticMethod(
                'TYPO3\CMS\Core\Utility\GeneralUtility',
                'isAbsPath',
                'TYPO3\CMS\Core\Utility\PathUtility',
                'isAbsolutePath'
            ),
        ]);
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
    $services->set(\Ssch\TYPO3Rector\Rector\v11\v5\RemoveDefaultInternalTypeDBRector::class);
    $services->set(\Ssch\TYPO3Rector\Rector\v11\v5\ReplaceTSFEATagParamsCallOnGlobalsRector::class);
    $services->set(\Ssch\TYPO3Rector\Rector\v11\v5\HandleCObjRendererATagParamsMethodRector::class);
    $services->set(\Ssch\TYPO3Rector\Rector\v11\v5\SubstituteBackendTemplateViewWithModuleTemplateRector::class);
    $services->set(
        \Ssch\TYPO3Rector\Rector\v11\v5\SubstituteGetIconFactoryAndGetPageRendererFromModuleTemplateRector::class
    );
};

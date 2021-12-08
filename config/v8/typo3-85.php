<?php

declare(strict_types=1);

use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\Rector\StaticCall\RenameStaticMethodRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use Rector\Renaming\ValueObject\RenameStaticMethod;
use Ssch\TYPO3Rector\Rector\v8\v5\CharsetConverterToMultiByteFunctionsRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/../config.php');

    $services = $containerConfigurator->services();
    $services->set(RenameMethodRector::class)
        ->configure([
            new MethodCallRename(
                'TYPO3\CMS\Backend\Clipboard\ClipBoard',
                'printContentFromTab',
                'getContentFromTab'
            ),
        ]);
    $services->set(CharsetConverterToMultiByteFunctionsRector::class);
    $services->set(RenameStaticMethodRector::class)
        ->configure([
            new RenameStaticMethod(
                'TYPO3\CMS\Extbase\Utility\ArrayUtility',
                'integerExplode',
                'TYPO3\CMS\Core\Utility\GeneralUtility',
                'intExplode'
            ),
            new RenameStaticMethod(
                'TYPO3\CMS\Extbase\Utility\ArrayUtility',
                'trimExplode',
                'TYPO3\CMS\Core\Utility\GeneralUtility',
                'trimExplode'
            ),
            new RenameStaticMethod(
                'TYPO3\CMS\Extbase\Utility\ArrayUtility',
                'getValueByPath',
                'TYPO3\CMS\Core\Utility\ArrayUtility',
                'getValueByPath'
            ),
            new RenameStaticMethod(
                'TYPO3\CMS\Extbase\Utility\ArrayUtility',
                'setValueByPath',
                'TYPO3\CMS\Core\Utility\ArrayUtility',
                'setValueByPath'
            ),
            new RenameStaticMethod(
                'TYPO3\CMS\Extbase\Utility\ArrayUtility',
                'unsetValueByPath',
                'TYPO3\CMS\Core\Utility\ArrayUtility',
                'removeByPath'
            ),
            new RenameStaticMethod(
                'TYPO3\CMS\Extbase\Utility\ArrayUtility',
                'sortArrayWithIntegerKeys',
                'TYPO3\CMS\Core\Utility\ArrayUtility',
                'sortArrayWithIntegerKeys'
            ),
        ]);
};

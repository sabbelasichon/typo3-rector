<?php

declare(strict_types=1);

use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\Rector\StaticCall\RenameStaticMethodRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use Rector\Renaming\ValueObject\RenameStaticMethod;
use Ssch\TYPO3Rector\Rector\v8\v5\CharsetConverterToMultiByteFunctionsRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\SymfonyPhpConfig\ValueObjectInliner;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/../config.php');

    $services = $containerConfigurator->services();
    $services->set('clip_board_print_content_from_tab_to_get_content_from_tab')
        ->class(RenameMethodRector::class)
        ->call(
        'configure',
        [[
            RenameMethodRector::METHOD_CALL_RENAMES => ValueObjectInliner::inline([
                new MethodCallRename(
                    'TYPO3\CMS\Backend\Clipboard\ClipBoard',
                    'printContentFromTab',
                    'getContentFromTab'
                ),
            ]),
        ]]
    );
    $services->set(CharsetConverterToMultiByteFunctionsRector::class);
    $services->set('extbase_array_utility_methods_to_core_array_utility_methods')
        ->class(RenameStaticMethodRector::class)
        ->call(
        'configure',
        [[
            RenameStaticMethodRector::OLD_TO_NEW_METHODS_BY_CLASSES => ValueObjectInliner::inline([
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
            ]),
        ]]
    );
};

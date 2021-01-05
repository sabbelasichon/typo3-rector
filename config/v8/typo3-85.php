<?php

declare(strict_types=1);

use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\Rector\StaticCall\RenameStaticMethodRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use Rector\Renaming\ValueObject\RenameStaticMethod;
use Ssch\TYPO3Rector\Rector\v8\v5\CharsetConverterToMultiByteFunctionsRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\SymfonyPhpConfig\ValueObjectInliner;
use TYPO3\CMS\Backend\Clipboard\ClipBoard;
use TYPO3\CMS\Core\Utility\ArrayUtility as CoreArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\ArrayUtility;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(RenameMethodRector::class)->call('configure', [[
        RenameMethodRector::METHOD_CALL_RENAMES => ValueObjectInliner::inline([
            new MethodCallRename(ClipBoard::class, 'printContentFromTab', 'getContentFromTab'),
        ]),
    ]]);
    $services->set(CharsetConverterToMultiByteFunctionsRector::class);
    $services->set(RenameStaticMethodRector::class)->call('configure', [[
        RenameStaticMethodRector::OLD_TO_NEW_METHODS_BY_CLASSES => ValueObjectInliner::inline([
            new RenameStaticMethod(ArrayUtility::class, 'integerExplode', GeneralUtility::class, 'intExplode'),
            new RenameStaticMethod(ArrayUtility::class, 'trimExplode', GeneralUtility::class, 'trimExplode'),
            new RenameStaticMethod(
                ArrayUtility::class,
                'getValueByPath',
                CoreArrayUtility::class,
                'getValueByPath'
            ),
            new RenameStaticMethod(
                ArrayUtility::class,
                'setValueByPath',
                CoreArrayUtility::class,
                'setValueByPath'
            ),
            new RenameStaticMethod(
                ArrayUtility::class,
                'unsetValueByPath',
                CoreArrayUtility::class,
                'removeByPath'
            ),
            new RenameStaticMethod(
                ArrayUtility::class,
                'sortArrayWithIntegerKeys',
                CoreArrayUtility::class,
                'sortArrayWithIntegerKeys'
            ),
        ]),
    ]]);
};

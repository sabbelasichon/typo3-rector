<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v9\v4;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\CodeSample;
use Rector\Core\RectorDefinition\RectorDefinition;
use TYPO3\CMS\Extbase\SignalSlot\Dispatcher;
use TYPO3\CMS\Extensionmanager\Service\ExtensionManagementService;
use TYPO3\CMS\Extensionmanager\Utility\InstallUtility;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/9.4/Deprecation-85462-SignalHasInstalledExtensions.html
 */
final class UseSignalAfterExtensionInstallInsteadOfHasInstalledExtensionsRector extends AbstractRector
{
    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    /**
     * @param MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isMethodStaticCallOrClassMethodObjectType($node, Dispatcher::class)) {
            return null;
        }

        if (! $this->isName($node->name, 'connect')) {
            return null;
        }

        if (! $this->isClassConstReference($node->args[0]->value, ExtensionManagementService::class)) {
            return null;
        }

        if (! $this->isValue($node->args[1]->value, 'hasInstalledExtensions')) {
            return null;
        }

        $node->args[0]->value = $this->createClassConstantReference(InstallUtility::class);
        $node->args[1]->value = $this->createArg('afterExtensionInstall');

        return null;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('Use the signal tablesDefinitionIsBeingBuilt of class SqlExpectedSchemaService', [
            new CodeSample(<<<'PHP'
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\SignalSlot\Dispatcher;
use TYPO3\CMS\Extensionmanager\Service\ExtensionManagementService;
$signalSlotDispatcher = GeneralUtility::makeInstance(Dispatcher::class);
$signalSlotDispatcher->connect(
        ExtensionManagementService::class,
        'hasInstalledExtensions',
        \stdClass::class,
        'foo'
    );
PHP
                , <<<'PHP'
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\SignalSlot\Dispatcher;
use TYPO3\CMS\Extensionmanager\Utility\InstallUtility;
$signalSlotDispatcher = GeneralUtility::makeInstance(Dispatcher::class);
    $signalSlotDispatcher->connect(
        InstallUtility::class,
        'afterExtensionInstall',
        \stdClass::class,
        'foo'
    );
PHP
            ),
        ]);
    }
}

<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\SignalSlot\Dispatcher;
use TYPO3\CMS\Extensionmanager\Service\ExtensionManagementService;

$signalSlotDispatcher = GeneralUtility::makeInstance(Dispatcher::class);
$signalSlotDispatcher->connect(
    ExtensionManagementService::class,
    'hasInstalledExtensions',
    stdClass::class,
    'foo'
);

?>
-----
<?php

use TYPO3\CMS\Extensionmanager\Utility\InstallUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\SignalSlot\Dispatcher;
use TYPO3\CMS\Extensionmanager\Service\ExtensionManagementService;

$signalSlotDispatcher = GeneralUtility::makeInstance(Dispatcher::class);
$signalSlotDispatcher->connect(
    InstallUtility::class,
    'afterExtensionInstall',
    stdClass::class,
    'foo'
);

?>

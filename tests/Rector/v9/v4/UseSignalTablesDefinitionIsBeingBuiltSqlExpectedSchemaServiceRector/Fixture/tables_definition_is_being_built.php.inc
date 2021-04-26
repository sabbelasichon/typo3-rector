<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\SignalSlot\Dispatcher;
use TYPO3\CMS\Extensionmanager\Utility\InstallUtility;

$signalSlotDispatcher = GeneralUtility::makeInstance(Dispatcher::class);
$signalSlotDispatcher->connect(
    InstallUtility::class,
    'tablesDefinitionIsBeingBuilt',
    stdClass::class,
    'foo'
);

?>
-----
<?php

use TYPO3\CMS\Install\Service\SqlExpectedSchemaService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\SignalSlot\Dispatcher;
use TYPO3\CMS\Extensionmanager\Utility\InstallUtility;

$signalSlotDispatcher = GeneralUtility::makeInstance(Dispatcher::class);
$signalSlotDispatcher->connect(
    SqlExpectedSchemaService::class,
    'tablesDefinitionIsBeingBuilt',
    stdClass::class,
    'foo'
);

?>

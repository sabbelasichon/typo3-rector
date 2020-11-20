<?php

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

ExtensionUtility::registerModule(
    'TYPO3.CMS.Beuser',
    'system',
    'tx_Beuser',
    'top',
    [
        'BackendUser' => 'index, addToCompareList, removeFromCompareList, compare, online, terminateBackendUserSession',
        'BackendUserGroup' => 'index'
    ],
    [
        'access' => 'admin',
        'icon' => 'EXT:beuser/Resources/Public/Icons/module-beuser.svg',
        'labels' => 'LLL:EXT:beuser/Resources/Private/Language/locallang_mod.xlf'
    ]
);

?>
-----
<?php

use TYPO3\CMS\Beuser\Controller\BackendUserController;
use TYPO3\CMS\Beuser\Controller\BackendUserGroupController;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
ExtensionUtility::registerModule(
    'Beuser',
    'system',
    'tx_Beuser',
    'top',
    [BackendUserController::class => 'index, addToCompareList, removeFromCompareList, compare, online, terminateBackendUserSession', BackendUserGroupController::class => 'index'],
    [
        'access' => 'admin',
        'icon' => 'EXT:beuser/Resources/Public/Icons/module-beuser.svg',
        'labels' => 'LLL:EXT:beuser/Resources/Private/Language/locallang_mod.xlf'
    ]
);

<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\MigrateBackendModuleRegistrationRector\Fixture;

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
    'Beuser',
    'system',
    'tx_Beuser',
    'top',
    [
        'TYPO3\CMS\Beuser\Controller\BackendUserController' => 'index, show, addToCompareList, removeFromCompareList, removeAllFromCompareList, compare, online, terminateBackendUserSession, initiatePasswordReset, groups, addGroupToCompareList, removeGroupFromCompareList, removeAllGroupsFromCompareList, compareGroups',
    ],
    [
        'access' => 'admin',
        'iconIdentifier' => 'module-beuser',
        'labels' => 'LLL:EXT:beuser/Resources/Private/Language/locallang_mod.xlf',
    ]
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addModule(
    'system',
    'BeuserTxPermission',
    'top',
    '',
    [
        'routeTarget' => 'TYPO3\CMS\Beuser\Controller\PermissionController::handleRequest',
        'name' => 'system_BeuserTxPermission',
        'access' => 'admin',
        'iconIdentifier' => 'module-permission',
        'labels' => 'LLL:EXT:beuser/Resources/Private/Language/locallang_mod_permission.xlf',
        'navigationComponentId' => 'TYPO3/CMS/Backend/PageTree/PageTreeElement',
    ]
);
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\MigrateBackendModuleRegistrationRector\Fixture;

?>

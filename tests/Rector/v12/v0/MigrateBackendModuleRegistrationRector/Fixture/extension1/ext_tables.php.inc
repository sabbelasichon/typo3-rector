<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\MigrateBackendModuleRegistrationRector\Fixture;

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addModule(
    'web',
    'example',
    'top',
    '',
    [
        'routeTarget' => 'Vendor\Extension\Controller\MyExampleModuleController::handleRequest',
        'name' => 'web_example',
        'access' => 'admin',
        'workspaces' => 'online',
        'iconIdentifier' => 'module-example',
        'labels' => 'LLL:EXT:example/Resources/Private/Language/locallang_mod.xlf',
        'navigationComponentId' => 'TYPO3/CMS/Backend/PageTree/PageTreeElement',
    ]
);
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\MigrateBackendModuleRegistrationRector\Fixture;

?>

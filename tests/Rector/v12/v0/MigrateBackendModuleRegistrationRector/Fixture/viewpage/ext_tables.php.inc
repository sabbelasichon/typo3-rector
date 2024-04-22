<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\MigrateBackendModuleRegistrationRector\Fixture;

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addModule(
    'web',
    'ViewpageView',
    'after:layout',
    null,
    [
        'routeTarget' => 'TYPO3\CMS\Viewpage\Controller\ViewModuleController::handleRequest',
        'access' => 'user,group',
        'name' => 'web_ViewpageView',
        'iconIdentifier' => 'module-viewpage',
        'labels' => 'LLL:EXT:viewpage/Resources/Private/Language/locallang_mod.xlf',
    ]
);
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\MigrateBackendModuleRegistrationRector\Fixture;

?>

<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v3\MoveSetupExtensionClassesToBackendRector\Fixture;

use TYPO3\CMS\Setup\Event\AddJavaScriptModulesEvent;
use TYPO3\CMS\Setup\Form\Element\AvatarElement;
use TYPO3\CMS\Setup\UserFunctions\UserSettingsItemsProcFunc;

final class SetupModuleListener
{
    public function __invoke(AddJavaScriptModulesEvent $event): void
    {
        $event->addJavaScriptModule('@my-extension/setupModule/some-file.js');
    }
}

final class MyAvatarElement extends AvatarElement
{
}

$itemsProcFunc = UserSettingsItemsProcFunc::class . '->renderLanguageSelect';
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v3\MoveSetupExtensionClassesToBackendRector\Fixture;

use TYPO3\CMS\Backend\Event\AddUserSettingsJavaScriptModulesEvent;
use TYPO3\CMS\Backend\Form\Element\AvatarElement;
use TYPO3\CMS\Backend\UserFunctions\UserSettingsItemsProcFunc;

final class SetupModuleListener
{
    public function __invoke(AddUserSettingsJavaScriptModulesEvent $event): void
    {
        $event->addJavaScriptModule('@my-extension/setupModule/some-file.js');
    }
}

final class MyAvatarElement extends AvatarElement
{
}

$itemsProcFunc = UserSettingsItemsProcFunc::class . '->renderLanguageSelect';
?>

<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v2\MoveLanguagePackCommandAndEventsToCoreRector\Fixture;

use TYPO3\CMS\Install\Command\LanguagePackCommand;
use TYPO3\CMS\Install\Service\Event\ModifyLanguagePackRemoteBaseUrlEvent;
use TYPO3\CMS\Install\Service\Event\ModifyLanguagePacksEvent;

final class MyEventListener
{
    public function modifyLanguagePacks(ModifyLanguagePacksEvent $event): void
    {
    }

    public function modifyRemoteBaseUrl(ModifyLanguagePackRemoteBaseUrlEvent $event): void
    {
    }
}

final class MyCommand extends LanguagePackCommand
{
}
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v2\MoveLanguagePackCommandAndEventsToCoreRector\Fixture;

use TYPO3\CMS\Core\Localization\Event\ModifyLanguagePacksEvent;
use TYPO3\CMS\Core\Localization\Event\ModifyLanguagePackRemoteBaseUrlEvent;
use TYPO3\CMS\Core\Command\UpdateLanguagePackCommand;

final class MyEventListener
{
    public function modifyLanguagePacks(ModifyLanguagePacksEvent $event): void
    {
    }

    public function modifyRemoteBaseUrl(ModifyLanguagePackRemoteBaseUrlEvent $event): void
    {
    }
}

final class MyCommand extends UpdateLanguagePackCommand
{
}
?>

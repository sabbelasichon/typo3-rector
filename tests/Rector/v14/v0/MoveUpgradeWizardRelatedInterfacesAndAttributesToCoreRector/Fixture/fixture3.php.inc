<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MoveUpgradeWizardRelatedInterfacesAndAttributesToCoreRector\Fixture;

use TYPO3\CMS\Core\Attribute\UpgradeWizard;
use TYPO3\CMS\Core\Upgrades\ChattyInterface;
use TYPO3\CMS\Core\Upgrades\ConfirmableInterface;
use TYPO3\CMS\Core\Upgrades\RepeatableInterface;
use TYPO3\CMS\Core\Upgrades\UpgradeWizardInterface;
use TYPO3\CMS\Install\Updates\DatabaseUpdatedPrerequisite;
use TYPO3\CMS\Install\Updates\ReferenceIndexUpdatedPrerequisite;

#[UpgradeWizard('myExtensionCustomUpgradeWizardIdentifier')]
class CustomUpgradeWizard implements UpgradeWizardInterface, ChattyInterface, RepeatableInterface
{
    public function getPrerequisites(): array
    {
        return [
            DatabaseUpdatedPrerequisite::class,
            ReferenceIndexUpdatedPrerequisite::class,
        ];
    }
}
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MoveUpgradeWizardRelatedInterfacesAndAttributesToCoreRector\Fixture;

use TYPO3\CMS\Core\Upgrades\DatabaseUpdatedPrerequisite;
use TYPO3\CMS\Core\Upgrades\ReferenceIndexUpdatedPrerequisite;
use TYPO3\CMS\Core\Attribute\UpgradeWizard;
use TYPO3\CMS\Core\Upgrades\ChattyInterface;
use TYPO3\CMS\Core\Upgrades\ConfirmableInterface;
use TYPO3\CMS\Core\Upgrades\RepeatableInterface;
use TYPO3\CMS\Core\Upgrades\UpgradeWizardInterface;

#[UpgradeWizard('myExtensionCustomUpgradeWizardIdentifier')]
class CustomUpgradeWizard implements UpgradeWizardInterface, ChattyInterface, RepeatableInterface
{
    public function getPrerequisites(): array
    {
        return [
            DatabaseUpdatedPrerequisite::class,
            ReferenceIndexUpdatedPrerequisite::class,
        ];
    }
}
?>

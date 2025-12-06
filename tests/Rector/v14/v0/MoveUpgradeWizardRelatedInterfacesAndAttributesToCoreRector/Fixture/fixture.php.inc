<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MoveUpgradeWizardRelatedInterfacesAndAttributesToCoreRector\Fixture;

use TYPO3\CMS\Install\Attribute\UpgradeWizard;
use TYPO3\CMS\Install\Updates\ChattyInterface;
use TYPO3\CMS\Install\Updates\ConfirmableInterface;
use TYPO3\CMS\Install\Updates\RepeatableInterface;
use TYPO3\CMS\Install\Updates\UpgradeWizardInterface;

#[UpgradeWizard('myExtensionCustomUpgradeWizardIdentifier')]
class CustomUpgradeWizard implements UpgradeWizardInterface, ChattyInterface, RepeatableInterface, ConfirmableInterface
{
    // ...
}
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MoveUpgradeWizardRelatedInterfacesAndAttributesToCoreRector\Fixture;

use TYPO3\CMS\Core\Attribute\UpgradeWizard;
use TYPO3\CMS\Core\Upgrades\UpgradeWizardInterface;
use TYPO3\CMS\Core\Upgrades\ChattyInterface;
use TYPO3\CMS\Core\Upgrades\RepeatableInterface;
use TYPO3\CMS\Core\Upgrades\ConfirmableInterface;

#[UpgradeWizard('myExtensionCustomUpgradeWizardIdentifier')]
class CustomUpgradeWizard implements UpgradeWizardInterface, ChattyInterface, RepeatableInterface, ConfirmableInterface
{
    // ...
}
?>

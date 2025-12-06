<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MoveUpgradeWizardRelatedInterfacesAndAttributesToCoreRector\Fixture;

use TYPO3\CMS\Install\Attribute\UpgradeWizard;
use TYPO3\CMS\Install\Updates\AbstractListTypeToCTypeUpdate;

#[UpgradeWizard('myExtensionCustomUpgradeWizardIdentifier')]
class CustomUpgradeWizard extends AbstractListTypeToCTypeUpdate
{
    // ...
}
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MoveUpgradeWizardRelatedInterfacesAndAttributesToCoreRector\Fixture;

use TYPO3\CMS\Core\Attribute\UpgradeWizard;
use TYPO3\CMS\Core\Upgrades\AbstractListTypeToCTypeUpdate;

#[UpgradeWizard('myExtensionCustomUpgradeWizardIdentifier')]
class CustomUpgradeWizard extends AbstractListTypeToCTypeUpdate
{
    // ...
}
?>

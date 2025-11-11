<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\AddNewMethodHasSubmoduleOverviewInModuleInterfaceRector\Fixture;

use TYPO3\CMS\Backend\Module\ModuleInterface;

class MyBackendModule implements ModuleInterface
{
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\AddNewMethodHasSubmoduleOverviewInModuleInterfaceRector\Fixture;

use TYPO3\CMS\Backend\Module\ModuleInterface;

class MyBackendModule implements ModuleInterface
{
    public function hasSubmoduleOverview(): bool
    {
        return $this->configuration['showSubmoduleOverview'] ?? false;
    }
}

?>

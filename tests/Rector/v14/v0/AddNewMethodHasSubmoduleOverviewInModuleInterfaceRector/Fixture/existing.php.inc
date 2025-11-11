<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\AddNewMethodHasSubmoduleOverviewInModuleInterfaceRector\Fixture;

use TYPO3\CMS\Backend\Module\ModuleInterface;

class DoNotModifyMyBackendModule implements ModuleInterface
{
    public function hasSubmoduleOverview(): bool
    {
        return false;
    }
}

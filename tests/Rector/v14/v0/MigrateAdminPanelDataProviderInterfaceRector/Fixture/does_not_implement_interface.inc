<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateAdminPanelDataProviderInterfaceRector\Fixture;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Adminpanel\ModuleApi\ModuleData;

class ClassDoesNotImplementInterface
{
    public function getDataToStore(ServerRequestInterface $request): ModuleData
    {
    }
}

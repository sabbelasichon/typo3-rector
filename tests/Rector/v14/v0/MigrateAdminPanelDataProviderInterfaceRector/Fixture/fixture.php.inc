<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateAdminPanelDataProviderInterfaceRector\Fixture;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Adminpanel\ModuleApi\DataProviderInterface;
use TYPO3\CMS\Adminpanel\ModuleApi\ModuleData;

class MyDataProvider implements DataProviderInterface
{
    public function getDataToStore(ServerRequestInterface $request): ModuleData
    {
    }
}
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateAdminPanelDataProviderInterfaceRector\Fixture;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Adminpanel\ModuleApi\DataProviderInterface;
use TYPO3\CMS\Adminpanel\ModuleApi\ModuleData;

class MyDataProvider implements DataProviderInterface
{
    public function getDataToStore(ServerRequestInterface $request, ResponseInterface $response): ModuleData
    {
    }
}
?>

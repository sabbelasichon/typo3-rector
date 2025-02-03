<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\MigrateGetControllerContextGetUriBuilderRector\Fixture;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class DummyController extends ActionController
{
    public function showAction(): ResponseInterface
    {
        $url = $this->getControllerContext()->getUriBuilder()
            ->setTargetPageType(10002)
            ->uriFor('addresses');
    }
}
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\MigrateGetControllerContextGetUriBuilderRector\Fixture;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class DummyController extends ActionController
{
    public function showAction(): ResponseInterface
    {
        $url = $this->uriBuilder
            ->setTargetPageType(10002)
            ->uriFor('addresses');
    }
}
?>

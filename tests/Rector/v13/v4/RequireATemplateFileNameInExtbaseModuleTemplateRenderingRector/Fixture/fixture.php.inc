<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v4\RequireATemplateFileNameInExtbaseModuleTemplateRenderingRector\Fixture;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Backend\Template\ModuleTemplate;

class CarController
{
    protected ModuleTemplate $moduleTemplate;

    public function __construct()
    {
        $this->moduleTemplate = new ModuleTemplate();
    }

    public function listAction(): ResponseInterface
    {
        return $this->moduleTemplate->renderResponse();
    }

    public function migratedAction(): ResponseInterface
    {
        return $this->moduleTemplate->renderResponse('Car/Migrated');
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v4\RequireATemplateFileNameInExtbaseModuleTemplateRenderingRector\Fixture;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Backend\Template\ModuleTemplate;

class CarController
{
    protected ModuleTemplate $moduleTemplate;

    public function __construct()
    {
        $this->moduleTemplate = new ModuleTemplate();
    }

    public function listAction(): ResponseInterface
    {
        return $this->moduleTemplate->renderResponse('Car/List');
    }

    public function migratedAction(): ResponseInterface
    {
        return $this->moduleTemplate->renderResponse('Car/Migrated');
    }
}

?>

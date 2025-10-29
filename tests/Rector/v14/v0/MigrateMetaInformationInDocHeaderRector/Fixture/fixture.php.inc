<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateMetaInformationInDocHeaderRector\Fixture;

use TYPO3\CMS\Backend\Template\ModuleTemplate;
use TYPO3\CMS\Core\Resource\File;

class MyClass
{
    public function foo()
    {
        $view = new ModuleTemplate();
        $pageInfo = [];
        $resource = new File();

        $view->getDocHeaderComponent()->setMetaInformation($pageInfo);
        $view->getDocHeaderComponent()->setMetaInformationForResource($resource);
    }
}
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateMetaInformationInDocHeaderRector\Fixture;

use TYPO3\CMS\Backend\Template\ModuleTemplate;
use TYPO3\CMS\Core\Resource\File;

class MyClass
{
    public function foo()
    {
        $view = new ModuleTemplate();
        $pageInfo = [];
        $resource = new File();

        $view->getDocHeaderComponent()->setPageBreadcrumb($pageInfo);
        $view->getDocHeaderComponent()->setResourceBreadcrumb($resource);
    }
}
?>

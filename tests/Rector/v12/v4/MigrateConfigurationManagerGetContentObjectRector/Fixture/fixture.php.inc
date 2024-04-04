<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v4\MigrateConfigurationManagerGetContentObjectRector\Fixture;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class MyActionController extends ActionController
{
    public function myMethod(): void
    {
        $contentObject = $this->configurationManager->getContentObject();
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v4\MigrateConfigurationManagerGetContentObjectRector\Fixture;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class MyActionController extends ActionController
{
    public function myMethod(): void
    {
        $contentObject = $this->request->getAttribute('currentContentObject');
    }
}

?>

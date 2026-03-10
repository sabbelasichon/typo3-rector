<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateTypoScriptFrontendControllerConfigRootLineRector\Fixture;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class MyController extends ActionController
{
    public function myAction(): void
    {
        $rootLine = $GLOBALS['TSFE']->config['rootLine'];
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateTypoScriptFrontendControllerConfigRootLineRector\Fixture;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class MyController extends ActionController
{
    public function myAction(): void
    {
        $rootLine = $this->request->getAttribute('frontend.page.information')->getLocalRootLine();
    }
}

?>

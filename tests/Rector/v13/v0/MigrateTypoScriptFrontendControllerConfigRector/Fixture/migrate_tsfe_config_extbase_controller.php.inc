<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateTypoScriptFrontendControllerConfigRector\Fixture;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class MyController extends ActionController
{
    public function myAction(): void
    {
        $config = $GLOBALS['TSFE']->config['config'];
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateTypoScriptFrontendControllerConfigRector\Fixture;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class MyController extends ActionController
{
    public function myAction(): void
    {
        $config = $this->request->getAttribute('frontend.typoscript')->getConfigArray();
    }
}

?>

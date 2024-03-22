<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v1\TemplateServiceToServerRequestFrontendTypoScriptAttributeRector\Fixture;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class MyController extends ActionController
{
    public function myMethod()
    {
        $setup = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_ext.']['settings.'];
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v1\TemplateServiceToServerRequestFrontendTypoScriptAttributeRector\Fixture;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class MyController extends ActionController
{
    public function myMethod()
    {
        $setup = $this->request->getAttribute('frontend.typoscript')->getSetupArray()['plugin.']['tx_ext.']['settings.'];
    }
}

?>

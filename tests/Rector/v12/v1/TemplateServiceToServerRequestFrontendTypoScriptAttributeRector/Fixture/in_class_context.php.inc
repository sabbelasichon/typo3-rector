<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v1\TemplateServiceToServerRequestFrontendTypoScriptAttributeRector\Fixture;

use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class MyClass
{
    public function myMethod()
    {
        $setup = $this->getTypoScriptFrontendController()->tmpl->setup;

        $setup2 = $this->getTypoScriptFrontendController()->tmpl->setup['config.'];
    }

    private function getTypoScriptFrontendController(): TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v1\TemplateServiceToServerRequestFrontendTypoScriptAttributeRector\Fixture;

use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class MyClass
{
    public function myMethod()
    {
        $setup = $GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.typoscript')->getSetupArray();

        $setup2 = $GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.typoscript')->getSetupArray()['config.'];
    }

    private function getTypoScriptFrontendController(): TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }
}

?>

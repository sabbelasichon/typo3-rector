<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateTypoScriptFrontendControllerConfigRootLineRector\Fixture;

use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class MyDirectAccessClass
{
    public function myMethod(TypoScriptFrontendController $tsfe): void
    {
        $rootLine = $tsfe->config['rootLine'];
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateTypoScriptFrontendControllerConfigRootLineRector\Fixture;

use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class MyDirectAccessClass
{
    public function myMethod(TypoScriptFrontendController $tsfe): void
    {
        $rootLine = $GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.page.information')->getLocalRootLine();
    }
}

?>

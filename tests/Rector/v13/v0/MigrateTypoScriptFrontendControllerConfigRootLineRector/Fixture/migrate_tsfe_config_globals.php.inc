<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateTypoScriptFrontendControllerConfigRootLineRector\Fixture;

class MyClass
{
    public function myMethod(): void
    {
        $rootLine = $GLOBALS['TSFE']->config['rootLine'];
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateTypoScriptFrontendControllerConfigRootLineRector\Fixture;

class MyClass
{
    public function myMethod(): void
    {
        $rootLine = $GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.page.information')->getLocalRootLine();
    }
}

?>

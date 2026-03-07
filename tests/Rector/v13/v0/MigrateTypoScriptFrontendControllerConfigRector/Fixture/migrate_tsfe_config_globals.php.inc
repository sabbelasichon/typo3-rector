<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateTypoScriptFrontendControllerConfigRector\Fixture;

class MyClass
{
    public function myMethod(): void
    {
        $config = $GLOBALS['TSFE']->config['config'];
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateTypoScriptFrontendControllerConfigRector\Fixture;

class MyClass
{
    public function myMethod(): void
    {
        $config = $GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.typoscript')->getConfigArray();
    }
}

?>

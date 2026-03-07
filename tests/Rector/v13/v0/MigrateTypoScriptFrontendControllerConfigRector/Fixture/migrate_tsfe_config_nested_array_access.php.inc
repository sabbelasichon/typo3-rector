<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateTypoScriptFrontendControllerConfigRector\Fixture;

class MyClassWithNestedArrayAccess
{
    public function myMethod(): void
    {
        $hint = $GLOBALS['TSFE']->config['config']['someKey'] ?? 'someDefault';
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateTypoScriptFrontendControllerConfigRector\Fixture;

class MyClassWithNestedArrayAccess
{
    public function myMethod(): void
    {
        $hint = $GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.typoscript')->getConfigArray()['someKey'] ?? 'someDefault';
    }
}

?>

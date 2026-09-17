<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateTypoScriptFrontendControllerReadOnlyPropertiesRector\Fixture;

use Psr\Http\Message\ServerRequestInterface;

class MyMiddleware
{
    public function process(ServerRequestInterface $request): void
    {
        $id = $GLOBALS['TSFE']->id;
        $page = $GLOBALS['TSFE']->page;
    }
}
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateTypoScriptFrontendControllerReadOnlyPropertiesRector\Fixture;

use Psr\Http\Message\ServerRequestInterface;

class MyMiddleware
{
    public function process(ServerRequestInterface $request): void
    {
        $id = $request->getAttribute('frontend.page.information')->getId();
        $page = $request->getAttribute('frontend.page.information')->getPageRecord();
    }
}
?>

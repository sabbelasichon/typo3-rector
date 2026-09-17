<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v1\TemplateServiceToServerRequestFrontendTypoScriptAttributeRector\Fixture;

use Psr\Http\Message\ServerRequestInterface;

class MyMiddleware
{
    public function process(ServerRequestInterface $request): void
    {
        $setup = $GLOBALS['TSFE']->tmpl->setup;
    }
}
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v1\TemplateServiceToServerRequestFrontendTypoScriptAttributeRector\Fixture;

use Psr\Http\Message\ServerRequestInterface;

class MyMiddleware
{
    public function process(ServerRequestInterface $request): void
    {
        $setup = $request->getAttribute('frontend.typoscript')->getSetupArray();
    }
}
?>

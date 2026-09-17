<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateTypoScriptFrontendControllerMethodCallsRector\Fixture;

use Psr\Http\Message\ServerRequestInterface;

class MyMiddleware
{
    public function process(ServerRequestInterface $request): void
    {
        $requestedId = $GLOBALS['TSFE']->getRequestedId();
        $site = $GLOBALS['TSFE']->getSite();
        $language = $GLOBALS['TSFE']->getLanguage();
        $pageArguments = $GLOBALS['TSFE']->getPageArguments();
    }
}
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateTypoScriptFrontendControllerMethodCallsRector\Fixture;

use Psr\Http\Message\ServerRequestInterface;

class MyMiddleware
{
    public function process(ServerRequestInterface $request): void
    {
        $requestedId = $request->getAttribute('routing')->getPageId();
        $site = $request->getAttribute('site');
        $language = $request->getAttribute('language') ?? $request->getAttribute('site')->getDefaultLanguage();
        $pageArguments = $request->getAttribute('routing');
    }
}
?>

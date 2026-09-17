<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateTypoScriptFrontendControllerConfigRector\Fixture;

use Psr\Http\Message\ServerRequestInterface;

class MyMiddleware
{
    public function process(ServerRequestInterface $request): void
    {
        $config = $GLOBALS['TSFE']->config['config'];
    }
}
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateTypoScriptFrontendControllerConfigRector\Fixture;

use Psr\Http\Message\ServerRequestInterface;

class MyMiddleware
{
    public function process(ServerRequestInterface $request): void
    {
        $config = $request->getAttribute('frontend.typoscript')->getConfigArray();
    }
}
?>

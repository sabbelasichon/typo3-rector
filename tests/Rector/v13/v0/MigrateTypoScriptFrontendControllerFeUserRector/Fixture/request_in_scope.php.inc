<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateTypoScriptFrontendControllerFeUserRector\Fixture;

use Psr\Http\Message\ServerRequestInterface;

class MyMiddleware
{
    public function process(ServerRequestInterface $request): void
    {
        $frontendUser = $GLOBALS['TSFE']->fe_user;
    }
}
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateTypoScriptFrontendControllerFeUserRector\Fixture;

use Psr\Http\Message\ServerRequestInterface;

class MyMiddleware
{
    public function process(ServerRequestInterface $request): void
    {
        $frontendUser = $request->getAttribute('frontend.user');
    }
}
?>

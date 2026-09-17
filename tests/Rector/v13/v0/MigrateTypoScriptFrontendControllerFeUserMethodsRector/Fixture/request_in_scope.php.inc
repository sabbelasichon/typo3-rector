<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateTypoScriptFrontendControllerFeUserMethodsRector\Fixture;

use Psr\Http\Message\ServerRequestInterface;

class MyMiddleware
{
    public function process(ServerRequestInterface $request): void
    {
        $GLOBALS['TSFE']->fe_user->setKey('ses', 'extension', 'value');
        $GLOBALS['TSFE']->fe_user->getKey('ses', 'extension');
    }
}
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateTypoScriptFrontendControllerFeUserMethodsRector\Fixture;

use Psr\Http\Message\ServerRequestInterface;

class MyMiddleware
{
    public function process(ServerRequestInterface $request): void
    {
        $request->getAttribute('frontend.user')->setKey('ses', 'extension', 'value');
        $request->getAttribute('frontend.user')->getKey('ses', 'extension');
    }
}
?>

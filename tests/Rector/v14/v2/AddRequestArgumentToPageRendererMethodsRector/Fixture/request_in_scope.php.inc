<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v2\AddRequestArgumentToPageRendererMethodsRector\Fixture;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Page\PageRenderer;

class MyMiddleware
{
    public function process(ServerRequestInterface $request, PageRenderer $pageRenderer): string
    {
        $pageRenderer->setLanguage($request->getAttribute('language')->getLocale());
        return $pageRenderer->render();
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v2\AddRequestArgumentToPageRendererMethodsRector\Fixture;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Page\PageRenderer;

class MyMiddleware
{
    public function process(ServerRequestInterface $request, PageRenderer $pageRenderer): string
    {
        $pageRenderer->setLanguage($request->getAttribute('language')->getLocale(), $request);
        return $pageRenderer->render($request);
    }
}

?>

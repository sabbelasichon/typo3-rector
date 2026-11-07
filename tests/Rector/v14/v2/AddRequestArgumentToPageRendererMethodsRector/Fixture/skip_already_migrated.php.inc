<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v2\AddRequestArgumentToPageRendererMethodsRector\Fixture;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Page\PageRenderer;

class AlreadyMigrated
{
    public function process(ServerRequestInterface $request, PageRenderer $pageRenderer): void
    {
        $pageRenderer->setLanguage($locale, $request);
        $pageRenderer->setDocType($docType, $request);
        $pageRenderer->render($request);
        $pageRenderer->renderResponse($request);
        $pageRenderer->renderResponse($request, 404);
        $pageRenderer->renderResponse($request, 404, 'Not Found');
        $pageRenderer->renderResponse(...$arguments);
    }
}

?>

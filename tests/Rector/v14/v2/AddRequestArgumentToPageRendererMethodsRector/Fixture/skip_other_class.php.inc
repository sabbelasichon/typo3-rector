<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v2\AddRequestArgumentToPageRendererMethodsRector\Fixture;

class OtherRenderer
{
    public function setLanguage($locale): void
    {
    }

    public function setDocType($docType): void
    {
    }

    public function render(): string
    {
        return '';
    }

    public function renderResponse(int $code = 200): void
    {
    }
}

$otherRenderer = new OtherRenderer();
$otherRenderer->setLanguage($locale);
$otherRenderer->setDocType($docType);
$otherRenderer->render();
$otherRenderer->renderResponse(404);

?>

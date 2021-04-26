<?php

use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;

final class ClassWithOldConcatenateMethodsPageRenderer
{
    /**
     * @var PageRenderer
     */
    private $pageRenderer;

    public function __construct(PageRenderer $pageRenderer)
    {
        $this->pageRenderer = $pageRenderer;
    }

    public function method(): void
    {
        $pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
        $files = $pageRenderer->getConcatenateFiles();

        $this->pageRenderer->enableConcatenateFiles();
        $this->pageRenderer->disableConcatenateFiles();

        $files2 = $this->pageRenderer->getConcatenateFiles();
    }
}

?>
-----
<?php

use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;

final class ClassWithOldConcatenateMethodsPageRenderer
{
    /**
     * @var PageRenderer
     */
    private $pageRenderer;

    public function __construct(PageRenderer $pageRenderer)
    {
        $this->pageRenderer = $pageRenderer;
    }

    public function method(): void
    {
        $pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
        $files = array_merge($pageRenderer->getConcatenateCss(), $pageRenderer->getConcatenateJavascript());

        $this->pageRenderer->enableConcatenateJavascript();
        $this->pageRenderer->enableConcatenateCss();
        $this->pageRenderer->disableConcatenateJavascript();
        $this->pageRenderer->disableConcatenateCss();

        $files2 = array_merge($this->pageRenderer->getConcatenateCss(), $this->pageRenderer->getConcatenateJavascript());
    }
}

?>

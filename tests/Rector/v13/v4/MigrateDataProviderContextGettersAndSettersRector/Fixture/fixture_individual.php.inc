<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v4\MigrateDataProviderContextGettersAndSettersRector\Fixture;

use TYPO3\CMS\Backend\View\BackendLayout\DataProviderContext;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class MyClass
{
    public function myMethod(): void
    {
        $dataProviderContext = GeneralUtility::makeInstance(DataProviderContext::class);
        $dataProviderContext->setPageId(1);
        $dataProviderContext->setData([]);
    }

    public function methodWithOtherStatementBetween(): void
    {
        $dataProviderContext = GeneralUtility::makeInstance(DataProviderContext::class);
        $dataProviderContext->setPageId(1);

        $foo = 'bar';

        $dataProviderContext->setData([]);
    }

    public function methodWithOtherMethodCallBetween(): void
    {
        $dataProviderContext = GeneralUtility::makeInstance(DataProviderContext::class);
        $dataProviderContext->setPageId(1);

        $pageRenderer = new PageRenderer();
        $pageRenderer->addMetaTag('example');

        $dataProviderContext->setData([]);
    }

    public function methodWithOtherStaticMethodCallBetween(): void
    {
        $dataProviderContext = GeneralUtility::makeInstance(DataProviderContext::class);
        $dataProviderContext->setPageId(1);

        $pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
        $pageRenderer->addMetaTag('example');

        $dataProviderContext->setData([]);
    }

    public function ifMethod(): void
    {
        $a = 'a';
        if ($a === 'b') {
            $dataProviderContext = GeneralUtility::makeInstance(DataProviderContext::class);
            $dataProviderContext->setPageId(1);
            $dataProviderContext->setData([]);
        }
    }

    public function foreachMethod(): void
    {
        $items = [];
        foreach ($items as $item) {
            $dataProviderContext = GeneralUtility::makeInstance(DataProviderContext::class);
            $dataProviderContext->setPageId(1);
            $dataProviderContext->setData([]);

            $a = $item;
        }
    }
}
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v4\MigrateDataProviderContextGettersAndSettersRector\Fixture;

use TYPO3\CMS\Backend\View\BackendLayout\DataProviderContext;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class MyClass
{
    public function myMethod(): void
    {
        $dataProviderContext = new DataProviderContext(pageId: 1, data: []);
    }

    public function methodWithOtherStatementBetween(): void
    {
        $dataProviderContext = new DataProviderContext(pageId: 1, data: []);

        $foo = 'bar';
    }

    public function methodWithOtherMethodCallBetween(): void
    {
        $dataProviderContext = new DataProviderContext(pageId: 1, data: []);

        $pageRenderer = new PageRenderer();
        $pageRenderer->addMetaTag('example');
    }

    public function methodWithOtherStaticMethodCallBetween(): void
    {
        $dataProviderContext = new DataProviderContext(pageId: 1, data: []);

        $pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
        $pageRenderer->addMetaTag('example');
    }

    public function ifMethod(): void
    {
        $a = 'a';
        if ($a === 'b') {
            $dataProviderContext = new DataProviderContext(pageId: 1, data: []);
        }
    }

    public function foreachMethod(): void
    {
        $items = [];
        foreach ($items as $item) {
            $dataProviderContext = new DataProviderContext(pageId: 1, data: []);

            $a = $item;
        }
    }
}
?>

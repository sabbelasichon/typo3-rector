<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\RemovePageRendererMethodsRector\Fixture;

use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);

$pageRenderer->disableConcatenateCss();
$pageRenderer->enableConcatenateCss();
$pageRenderer->getConcatenateCss();
$pageRenderer->disableCompressCss();
$pageRenderer->enableCompressCss();
$pageRenderer->getCompressCss();

$pageRenderer->disableConcatenateJavascript();
$pageRenderer->enableConcatenateJavascript();
$pageRenderer->getConcatenateJavascript();
$pageRenderer->disableCompressJavascript();
$pageRenderer->enableCompressJavascript();
$pageRenderer->getCompressJavascript();

$pageRenderer->addCssFile('foo.css');
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\RemovePageRendererMethodsRector\Fixture;

use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);

$pageRenderer->addCssFile('foo.css');
?>

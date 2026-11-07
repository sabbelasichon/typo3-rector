<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v2\AddRequestArgumentToPageRendererMethodsRector\Fixture;

use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
$pageRenderer->setLanguage($locale);
$pageRenderer->setDocType($docType);
$content = $pageRenderer->render();
$response = $pageRenderer->renderResponse();
$response = $pageRenderer->renderResponse(404);
$response = $pageRenderer->renderResponse(404, 'Not Found');

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v2\AddRequestArgumentToPageRendererMethodsRector\Fixture;

use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
$pageRenderer->setLanguage($locale, $GLOBALS['TYPO3_REQUEST']);
$pageRenderer->setDocType($docType, $GLOBALS['TYPO3_REQUEST']);
$content = $pageRenderer->render($GLOBALS['TYPO3_REQUEST']);
$response = $pageRenderer->renderResponse($GLOBALS['TYPO3_REQUEST']);
$response = $pageRenderer->renderResponse($GLOBALS['TYPO3_REQUEST'], 404);
$response = $pageRenderer->renderResponse($GLOBALS['TYPO3_REQUEST'], 404, 'Not Found');

?>

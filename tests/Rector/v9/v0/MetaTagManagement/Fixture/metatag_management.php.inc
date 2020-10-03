<?php

use TYPO3\CMS\Backend\Template\DocumentTemplate;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
$pageRenderer->addMetaTag('<meta name="keywords" content="seo, search engine optimisation, search engine optimization, search engine ranking">');
$pageRenderer->addMetaTag('<meta content="summary" name="twitter:card" />');
$pageRenderer->addMetaTag('<meta property="og:title" content="Your eye-catching title here" />');
$pageRenderer->addMetaTag('<meta http-equiv="X-UA-Compatible" content="IE=edge">');

$documentTemplate = GeneralUtility::makeInstance(DocumentTemplate::class);
$documentTemplate->xUaCompatible();
$documentTemplate->xUaCompatible('IE=9');

?>
-----
<?php

use TYPO3\CMS\Backend\Template\DocumentTemplate;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
$pageRenderer->setMetaTag('name', 'keywords', 'seo, search engine optimisation, search engine optimization, search engine ranking');
$pageRenderer->setMetaTag('name', 'twitter:card', 'summary');
$pageRenderer->setMetaTag('property', 'og:title', 'Your eye-catching title here');
$pageRenderer->setMetaTag('http-equiv', 'X-UA-Compatible', 'IE=edge');

$documentTemplate = GeneralUtility::makeInstance(DocumentTemplate::class);
GeneralUtility::makeInstance(PageRenderer::class)->setMetaTag('http-equiv', 'X-UA-Compatible', 'IE=8');
GeneralUtility::makeInstance(PageRenderer::class)->setMetaTag('http-equiv', 'X-UA-Compatible', 'IE=9');

?>

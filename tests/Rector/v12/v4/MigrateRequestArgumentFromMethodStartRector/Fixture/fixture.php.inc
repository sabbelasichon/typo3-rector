<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v4\MigrateRequestArgumentFromMethodStartRector\Fixture;

use Ssch\TYPO3Rector\Tests\Rector\v12\v4\MigrateRequestArgumentFromMethodStartRector\Source\MyServerRequest;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

$serverRequest = new MyServerRequest();
$contentObjectRenderer = GeneralUtility::makeInstance(ContentObjectRenderer::class);
$contentObjectRenderer->start([], 'pages', $serverRequest);

$anotherContentObjectRenderer = GeneralUtility::makeInstance(ContentObjectRenderer::class);
$anotherContentObjectRenderer->setRequest($serverRequest);
$anotherContentObjectRenderer->start([], 'pages');

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v4\MigrateRequestArgumentFromMethodStartRector\Fixture;

use Ssch\TYPO3Rector\Tests\Rector\v12\v4\MigrateRequestArgumentFromMethodStartRector\Source\MyServerRequest;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

$serverRequest = new MyServerRequest();
$contentObjectRenderer = GeneralUtility::makeInstance(ContentObjectRenderer::class);
$contentObjectRenderer->setRequest($serverRequest);
$contentObjectRenderer->start([], 'pages');

$anotherContentObjectRenderer = GeneralUtility::makeInstance(ContentObjectRenderer::class);
$anotherContentObjectRenderer->setRequest($serverRequest);
$anotherContentObjectRenderer->start([], 'pages');

?>

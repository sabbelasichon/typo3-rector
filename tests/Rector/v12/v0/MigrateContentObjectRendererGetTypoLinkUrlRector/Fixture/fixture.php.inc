<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\MigrateContentObjectRendererGetTypoLinkUrlRector\Fixture;

use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

$contentObjectRenderer = new ContentObjectRenderer();
$url = $contentObjectRenderer->getTypoLink_URL(12);
$urlWithString = $contentObjectRenderer->getTypoLink_URL(12, '&foo=bar');
$urlWithArray = $contentObjectRenderer->getTypoLink_URL(12, ['foo' => 'bar']);
$urlWithArrayAndTarget = $contentObjectRenderer->getTypoLink_URL(12, ['foo' => 'bar'], '_blank');

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\MigrateContentObjectRendererGetTypoLinkUrlRector\Fixture;

use TYPO3\CMS\Core\Utility\HttpUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

$contentObjectRenderer = new ContentObjectRenderer();
$url = $contentObjectRenderer->createUrl(['parameter' => 12]);
$urlWithString = $contentObjectRenderer->createUrl(['parameter' => 12, 'additionalParams' => '&foo=bar']);
$urlWithArray = $contentObjectRenderer->createUrl(['parameter' => 12, 'additionalParams' => HttpUtility::buildQueryString(['foo' => 'bar'], '&')]);
$urlWithArrayAndTarget = $contentObjectRenderer->createUrl(['parameter' => 12, 'additionalParams' => HttpUtility::buildQueryString(['foo' => 'bar'], '&'), 'target' => '_blank', 'extTarget' => '_blank', 'fileTarget' => '_blank']);

?>

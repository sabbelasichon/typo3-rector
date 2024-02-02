<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\MigrateContentObjectRendererLastTypoLinkPropertiesRector\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

$contentObjectRenderer = GeneralUtility::makeInstance(ContentObjectRenderer::class);

$lastTypoLinkUrl = $contentObjectRenderer->lastTypoLinkUrl;
$lastTypoLinkTarget = $contentObjectRenderer->lastTypoLinkTarget;
$lastTypoLinkLD = $contentObjectRenderer->lastTypoLinkLD;
$fooBar = $contentObjectRenderer->parameters;

$myCallback = function() use($contentObjectRenderer) {
    return $contentObjectRenderer->lastTypoLinkUrl;
};

$localContentObjectRenderer = GeneralUtility::makeInstance(ContentObjectRenderer::class);

$localContentObjectRenderer->lastTypoLinkUrl = $contentObjectRenderer->lastTypoLinkUrl;

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\MigrateContentObjectRendererLastTypoLinkPropertiesRector\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

$contentObjectRenderer = GeneralUtility::makeInstance(ContentObjectRenderer::class);

$lastTypoLinkUrl = $contentObjectRenderer->lastTypoLinkResult->getUrl();
$lastTypoLinkTarget = $contentObjectRenderer->lastTypoLinkResult->getTarget();
$lastTypoLinkLD = ['target' => htmlspecialchars($contentObjectRenderer->lastTypoLinkResult->getTarget()), 'totalUrl' => $contentObjectRenderer->lastTypoLinkResult->getUrl(), 'type' => $contentObjectRenderer->lastTypoLinkResult->getType()];
$fooBar = $contentObjectRenderer->parameters;

$myCallback = function() use($contentObjectRenderer) {
    return $contentObjectRenderer->lastTypoLinkResult->getUrl();
};

$localContentObjectRenderer = GeneralUtility::makeInstance(ContentObjectRenderer::class);

$localContentObjectRenderer->lastTypoLinkUrl = $contentObjectRenderer->lastTypoLinkResult->getUrl();

?>

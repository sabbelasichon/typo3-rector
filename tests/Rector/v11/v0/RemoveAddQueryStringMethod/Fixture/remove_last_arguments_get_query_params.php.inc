<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v0\RemoveAddQueryStringMethod\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

$contentObjectRenderer = GeneralUtility::makeInstance(ContentObjectRenderer::class);
$contentObjectRenderer->getQueryArguments(['foo'], [], true);

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v0\RemoveAddQueryStringMethod\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

$contentObjectRenderer = GeneralUtility::makeInstance(ContentObjectRenderer::class);
$contentObjectRenderer->getQueryArguments(['foo']);

?>

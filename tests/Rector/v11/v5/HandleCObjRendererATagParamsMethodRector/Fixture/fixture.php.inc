<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v5\ReplaceTSFEATagParamsCallOnGlobalsRector\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

$cObjRenderer = GeneralUtility::makeInstance(ContentObjectRenderer::class);
$bar = $cObjRenderer->getATagParams([], false);

$foo = $cObjRenderer->getATagParams([], true);
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v5\ReplaceTSFEATagParamsCallOnGlobalsRector\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

$cObjRenderer = GeneralUtility::makeInstance(ContentObjectRenderer::class);
$bar = $cObjRenderer->getATagParams([], false);

$foo = $cObjRenderer->getATagParams([]);
?>

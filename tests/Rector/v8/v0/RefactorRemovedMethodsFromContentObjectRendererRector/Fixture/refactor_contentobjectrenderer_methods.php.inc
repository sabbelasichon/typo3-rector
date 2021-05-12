<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\Frontend\ContentObject\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

final class ContentObjectRendererMethods
{
    public function doSomething(): void
    {
        $otherRecords = $GLOBALS['TSFE']->cObj->RECORDS(['tables' => 'tt_content', 'source' => '1,2,3']);
        $keepRecords = $GLOBALS['TSFES']->cObj->RECORDS(['tables' => 'tt_content', 'source' => '1,2,3']);

        $cObj = GeneralUtility::makeInstance(ContentObjectRenderer::class);
        $cObj->RECORDS(['tables' => 'tt_content', 'source' => '1,2,3']);
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\Frontend\ContentObject\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

final class ContentObjectRendererMethods
{
    public function doSomething(): void
    {
        $otherRecords = $GLOBALS['TSFE']->cObj->cObjGetSingle('RECORDS', ['tables' => 'tt_content', 'source' => '1,2,3']);
        $keepRecords = $GLOBALS['TSFES']->cObj->RECORDS(['tables' => 'tt_content', 'source' => '1,2,3']);

        $cObj = GeneralUtility::makeInstance(ContentObjectRenderer::class);
        $cObj->cObjGetSingle('RECORDS', ['tables' => 'tt_content', 'source' => '1,2,3']);
    }
}

?>

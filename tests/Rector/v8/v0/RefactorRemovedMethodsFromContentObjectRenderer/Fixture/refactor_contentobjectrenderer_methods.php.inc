<?php

namespace Ssch\TYPO3Rector\Tests\Rector\Frontend\ContentObject\Fixture;

use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

final class ContentObjectRendererMethods
{
    public function doSomething(): void
    {
        $this->otherRecords = $GLOBALS['TSFE']->cObj->RECORDS(['tables' => 'tt_content', 'source' => '1,2,3']);
        $this->keepRecords = $GLOBALS['TSFES']->cObj->RECORDS(['tables' => 'tt_content', 'source' => '1,2,3']);
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\Frontend\ContentObject\Fixture;

use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

final class ContentObjectRendererMethods
{
    public function doSomething(): void
    {
        $this->otherRecords = $GLOBALS['TSFE']->cObj->cObjGetSingle('RECORDS', ['tables' => 'tt_content', 'source' => '1,2,3']);
        $this->keepRecords = $GLOBALS['TSFES']->cObj->RECORDS(['tables' => 'tt_content', 'source' => '1,2,3']);
    }
}

?>

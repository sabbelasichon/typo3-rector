<?php

namespace Ssch\TYPO3Rector\Tests\Rector\Frontend\ContentObject\Fixture;

use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

final class ContentObjectRendererMethodsFrontendController
{
    public function doSomethingElse(): void
    {
        $records = $this->getTypoScriptFrontendController()->cObj->RECORDS(['tables' => 'tt_content', 'source' => '1,2,3']);

        $typoscriptFrontendController = $this->getTypoScriptFrontendController();
        $typoscriptFrontendController->cObj->RECORDS(['tables' => 'tt_content', 'source' => '1,2,3']);
    }

    private function getTypoScriptFrontendController(): TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\Frontend\ContentObject\Fixture;

use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

final class ContentObjectRendererMethodsFrontendController
{
    public function doSomethingElse(): void
    {
        $records = $this->getTypoScriptFrontendController()->cObj->cObjGetSingle('RECORDS', ['tables' => 'tt_content', 'source' => '1,2,3']);

        $typoscriptFrontendController = $this->getTypoScriptFrontendController();
        $typoscriptFrontendController->cObj->cObjGetSingle('RECORDS', ['tables' => 'tt_content', 'source' => '1,2,3']);
    }

    private function getTypoScriptFrontendController(): TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }
}

?>

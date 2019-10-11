<?php

use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

final class ContentObjectRendererMethods
{
    public function doSomething(): void
    {
        $this->records = $this->getTypoScriptFrontendController()->cObj->RECORDS(['tables' => 'tt_content', 'source' => '1,2,3']);
    }

    /**
     * @return TypoScriptFrontendController
     */
    private function getTypoScriptFrontendController(): TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }
}

?>
-----
<?php

use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

final class ContentObjectRendererMethods
{
    public function doSomething(): void
    {
        $this->records = $this->getTypoScriptFrontendController()->cObj->cObjGetSingle('RECORDS', ['tables' => 'tt_content', 'source' => '1,2,3']);
    }

    /**
     * @return TypoScriptFrontendController
     */
    private function getTypoScriptFrontendController(): TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }
}

?>

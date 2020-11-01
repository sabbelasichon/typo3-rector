<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

final class RemoveInitTemplateMethod
{
    public function doSomething(): void
    {
        $this->getTypoScriptFrontendController()->initTemplate();
        $typoScriptFrontendController = GeneralUtility::makeInstance(TypoScriptFrontendController::class);
        $typoScriptFrontendController->initTemplate();
        $GLOBALS['TSFE']->initTemplate();
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

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

final class RemoveInitTemplateMethod
{
    public function doSomething(): void
    {
        $typoScriptFrontendController = GeneralUtility::makeInstance(TypoScriptFrontendController::class);
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

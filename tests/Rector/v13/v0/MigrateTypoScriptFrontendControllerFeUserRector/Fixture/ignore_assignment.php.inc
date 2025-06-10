<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateTypoScriptFrontendControllerFeUserRector\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class IgnoreAssignment
{
    public function myMethod(): void
    {
        $GLOBALS['TSFE']->fe_user = GeneralUtility::makeInstance(FrontendUserAuthentication::class);
    }

    public function myActionWithTypoScriptFrontendController(): void
    {
        $this->getTypoScriptFrontendController()->fe_user = GeneralUtility::makeInstance(FrontendUserAuthentication::class);
    }

    private function getTypoScriptFrontendController(): TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }
}

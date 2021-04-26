<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v9\v5\RefactorPropertiesOfTypoScriptFrontendControllerRector\Fixture;

use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

final class FooBarBazTypoScriptFrontendController
{
    public function doSomething(): void
    {
        $this->getTypoScriptFrontendController()->loginAllowedInBranch = false;
        $previewBeUserUid = $this->getTypoScriptFrontendController()->ADMCMD_preview_BEUSER_uid;
        $workspacePreview = $this->getTypoScriptFrontendController()->workspacePreview;
        $loginAllowedInBranch = $this->getTypoScriptFrontendController()->loginAllowedInBranch;
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

namespace Ssch\TYPO3Rector\Tests\Rector\v9\v5\RefactorPropertiesOfTypoScriptFrontendControllerRector\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

final class FooBarBazTypoScriptFrontendController
{
    public function doSomething(): void
    {
        $this->getTypoScriptFrontendController()->loginAllowedInBranch = false;
        $previewBeUserUid = GeneralUtility::makeInstance(Context::class)->getPropertyFromAspect('backend.user', 'id', 0);
        $workspacePreview = GeneralUtility::makeInstance(Context::class)->getPropertyFromAspect('workspace', 'id', 0);
        $loginAllowedInBranch = $this->getTypoScriptFrontendController()->checkIfLoginAllowedInBranch();
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

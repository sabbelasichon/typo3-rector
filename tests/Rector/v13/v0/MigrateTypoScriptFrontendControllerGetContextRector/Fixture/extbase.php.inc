<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateTypoScriptFrontendControllerGetContextRector\Fixture;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class MyClass extends ActionController
{
    public function myAction(): void
    {
        $context = $GLOBALS['TSFE']->getContext();
    }

    public function myActionWithTypoScriptFrontendController(): void
    {
        $context = $this->getTypoScriptFrontendController()->getContext();
        $date = $this->getTypoScriptFrontendController()->getContext()->getAspect('date');
    }

    private function getTypoScriptFrontendController(): TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateTypoScriptFrontendControllerGetContextRector\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class MyClass extends ActionController
{
    public function myAction(): void
    {
        $context = GeneralUtility::makeInstance(Context::class);
    }

    public function myActionWithTypoScriptFrontendController(): void
    {
        $context = GeneralUtility::makeInstance(Context::class);
        $date = GeneralUtility::makeInstance(Context::class)->getAspect('date');
    }

    private function getTypoScriptFrontendController(): TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }
}

?>

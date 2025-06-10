<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateTypoScriptFrontendControllerFeUserRector\Fixture;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class MyClass extends ActionController
{
    public function myAction(): void
    {
        if (is_array($GLOBALS['TSFE']->fe_user->user)
            && $GLOBALS['TSFE']->fe_user->user['uid'] > 0
        ) {
            $id = $GLOBALS['TSFE']->fe_user->user['uid'];
        }
    }

    public function myActionWithTypoScriptFrontendController(): void
    {
        if (is_array($this->getTypoScriptFrontendController()->fe_user->user)
            && $this->getTypoScriptFrontendController()->fe_user->user['uid'] > 0
        ) {
            $id = $this->getTypoScriptFrontendController()->fe_user->user['uid'];
        }
    }

    private function getTypoScriptFrontendController(): TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }
}
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateTypoScriptFrontendControllerFeUserRector\Fixture;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class MyClass extends ActionController
{
    public function myAction(): void
    {
        if (is_array($this->request->getAttribute('frontend.user')->user)
            && $this->request->getAttribute('frontend.user')->user['uid'] > 0
        ) {
            $id = $this->request->getAttribute('frontend.user')->user['uid'];
        }
    }

    public function myActionWithTypoScriptFrontendController(): void
    {
        if (is_array($this->request->getAttribute('frontend.user')->user)
            && $this->request->getAttribute('frontend.user')->user['uid'] > 0
        ) {
            $id = $this->request->getAttribute('frontend.user')->user['uid'];
        }
    }

    private function getTypoScriptFrontendController(): TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }
}
?>

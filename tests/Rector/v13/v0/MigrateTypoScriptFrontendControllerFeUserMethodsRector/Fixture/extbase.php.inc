<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateTypoScriptFrontendControllerFeUserMethodsRector\Fixture;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class MyClass extends ActionController
{
    public function myAction(): void
    {
        $GLOBALS['TSFE']->fe_user->setKey('ses', 'extension', 'value');
        $foo = $GLOBALS['TSFE']->fe_user->getKey('ses', 'extension');
        $GLOBALS['TSFE']->fe_user->storeSessionData();
    }

    public function myActionWithTypoScriptFrontendController(): void
    {
        $this->getTypoScriptFrontendController()->fe_user->setKey('ses', 'extension', 'value');
        $foo = $this->getTypoScriptFrontendController()->fe_user->getKey('ses', 'extension');
        $this->getTypoScriptFrontendController()->fe_user->storeSessionData();
    }

    private function getTypoScriptFrontendController(): TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateTypoScriptFrontendControllerFeUserMethodsRector\Fixture;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class MyClass extends ActionController
{
    public function myAction(): void
    {
        $this->request->getAttribute('frontend.user')->setKey('ses', 'extension', 'value');
        $foo = $this->request->getAttribute('frontend.user')->getKey('ses', 'extension');
        $this->request->getAttribute('frontend.user')->storeSessionData();
    }

    public function myActionWithTypoScriptFrontendController(): void
    {
        $this->request->getAttribute('frontend.user')->setKey('ses', 'extension', 'value');
        $foo = $this->request->getAttribute('frontend.user')->getKey('ses', 'extension');
        $this->request->getAttribute('frontend.user')->storeSessionData();
    }

    private function getTypoScriptFrontendController(): TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }
}

?>

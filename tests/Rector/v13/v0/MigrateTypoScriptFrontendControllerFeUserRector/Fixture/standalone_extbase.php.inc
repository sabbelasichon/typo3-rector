<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateTypoScriptFrontendControllerFeUserRector\Fixture;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class StandaloneExtbase extends ActionController
{
    public function returnFeUser()
    {
        return $GLOBALS['TSFE']->fe_user;
    }

    public function conditionFeUser(): void
    {
        if ($GLOBALS['TSFE']->fe_user) {
            echo 'has user';
        }
    }
}
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateTypoScriptFrontendControllerFeUserRector\Fixture;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class StandaloneExtbase extends ActionController
{
    public function returnFeUser()
    {
        return $this->request->getAttribute('frontend.user');
    }

    public function conditionFeUser(): void
    {
        if ($this->request->getAttribute('frontend.user')) {
            echo 'has user';
        }
    }
}
?>

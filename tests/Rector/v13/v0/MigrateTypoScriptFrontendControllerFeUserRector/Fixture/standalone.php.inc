<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateTypoScriptFrontendControllerFeUserRector\Fixture;

class Standalone
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

    public function argumentFeUser(): void
    {
        $this->doSomething($GLOBALS['TSFE']->fe_user);
    }

    private function doSomething($user): void
    {
    }
}
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateTypoScriptFrontendControllerFeUserRector\Fixture;

class Standalone
{
    public function returnFeUser()
    {
        return $GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.user');
    }

    public function conditionFeUser(): void
    {
        if ($GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.user')) {
            echo 'has user';
        }
    }

    public function argumentFeUser(): void
    {
        $this->doSomething($GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.user'));
    }

    private function doSomething($user): void
    {
    }
}
?>

<?php

use TYPO3\CMS\Extbase\Mvc\Controller\CommandController;

/**
 * @internal
 */
final class TestCommandControllerWithUserAuthentication extends CommandController
{
    public function myMethod()
    {
        $userAuthentication = $this->userAuthentication;
        if($this->userAuthentication !== null) {

        }
    }
}

?>
-----
<?php

use TYPO3\CMS\Extbase\Mvc\Controller\CommandController;

final class TestCommandControllerWithUserAuthentication extends CommandController
{
    public function myMethod()
    {
        $userAuthentication = $this->getBackendUserAuthentication();
        if($this->getBackendUserAuthentication() !== null) {

        }
    }
}

?>

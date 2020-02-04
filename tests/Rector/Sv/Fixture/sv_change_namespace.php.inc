<?php

use TYPO3\CMS\Sv\AbstractAuthenticationService;
use TYPO3\CMS\Sv\AuthenticationService;

final class MyAbstractAuthenticationService extends AbstractAuthenticationService
{
}

final class MyAuthenticationService extends AuthenticationService
{
}

final class MyAuthenticationServiceUser
{
    /**
     * @var AuthenticationService
     */
    private $authenticationService;

    public function __construct(AuthenticationService $authenticationService)
    {
        $this->authenticationService = $authenticationService;
    }
}

?>
-----
<?php

use TYPO3\CMS\Core\Authentication\AbstractAuthenticationService;
use TYPO3\CMS\Core\Authentication\AuthenticationService;

final class MyAbstractAuthenticationService extends \TYPO3\CMS\Core\Authentication\AbstractAuthenticationService
{
}

final class MyAuthenticationService extends \TYPO3\CMS\Core\Authentication\AuthenticationService
{
}

final class MyAuthenticationServiceUser
{
    /**
     * @var AuthenticationService
     */
    private $authenticationService;

    public function __construct(\TYPO3\CMS\Core\Authentication\AuthenticationService $authenticationService)
    {
        $this->authenticationService = $authenticationService;
    }
}

?>

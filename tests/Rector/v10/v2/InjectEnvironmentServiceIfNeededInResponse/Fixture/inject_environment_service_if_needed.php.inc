<?php

use TYPO3\CMS\Extbase\Mvc\Web\Response;

class MyResponse extends Response
{
    public function myMethod()
    {
        if ($this->environmentService->isEnvironmentInCliMode()) {

        }
    }
}

class MyOtherResponse extends Response
{
    public function myMethod()
    {

    }
}

?>
-----
<?php

use TYPO3\CMS\Extbase\Service\EnvironmentService;
use TYPO3\CMS\Extbase\Mvc\Web\Response;
class MyResponse extends Response
{
    /**
     * @var \TYPO3\CMS\Extbase\Service\EnvironmentService
     */
    protected $environmentService;

    public function myMethod()
    {
        if ($this->environmentService->isEnvironmentInCliMode()) {

        }
    }

    public function injectEnvironmentService(EnvironmentService $environmentService)
    {
        $this->environmentService = $environmentService;
    }
}
class MyOtherResponse extends Response
{
    public function myMethod()
    {

    }
}

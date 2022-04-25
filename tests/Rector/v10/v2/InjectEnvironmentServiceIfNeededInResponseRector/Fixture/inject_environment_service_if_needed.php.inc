<?php

namespace Ssch\TYPO3Rector\Tests;

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

namespace Ssch\TYPO3Rector\Tests;

use TYPO3\CMS\Extbase\Service\EnvironmentService;
use TYPO3\CMS\Extbase\Mvc\Web\Response;

class MyResponse extends Response
{
    /**
     * @var EnvironmentService
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

?>

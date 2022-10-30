<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v2\typo3\SubstituteEnvironmentServiceWithApplicationTypeRector\Fixture;

use Ssch\TYPO3Rector\Tests\Rector\v11\v2\typo3\SubstituteEnvironmentServiceWithApplicationTypeRector\Source\MyCustomEnvironment;
use TYPO3\CMS\Extbase\Service\EnvironmentService;

class MyService
{
    private EnvironmentService $environmentService;
    private MyCustomEnvironment $myCustomEnvironment;

    public function __construct(EnvironmentService $environmentService, MyCustomEnvironment $myCustomEnvironment)
    {
        $this->environmentService = $environmentService;
        $this->myCustomEnvironment = $myCustomEnvironment;
    }

    public function myMethod()
    {
        if($this->environmentService->isEnvironmentInFrontendMode()) {
            echo 'frontend';
        }

        if($this->environmentService->isEnvironmentInBackendMode()) {
            echo 'backend';
        }

        if($this->environmentService->isEnvironmentInCliMode()) {
            echo 'cli';
        }

        if($this->myCustomEnvironment->isEnvironmentInBackendMode()) {
            echo 'backend';
        }

        if($this->myCustomEnvironment->isEnvironmentInFrontendMode()) {
            echo 'backend';
        }
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v2\typo3\SubstituteEnvironmentServiceWithApplicationTypeRector\Fixture;

use TYPO3\CMS\Core\Http\ApplicationType;
use Ssch\TYPO3Rector\Tests\Rector\v11\v2\typo3\SubstituteEnvironmentServiceWithApplicationTypeRector\Source\MyCustomEnvironment;
use TYPO3\CMS\Extbase\Service\EnvironmentService;

class MyService
{
    private EnvironmentService $environmentService;
    private MyCustomEnvironment $myCustomEnvironment;

    public function __construct(EnvironmentService $environmentService, MyCustomEnvironment $myCustomEnvironment)
    {
        $this->environmentService = $environmentService;
        $this->myCustomEnvironment = $myCustomEnvironment;
    }

    public function myMethod()
    {
        if(ApplicationType::fromRequest($GLOBALS['TYPO3_REQUEST'])->isFrontend()) {
            echo 'frontend';
        }

        if(ApplicationType::fromRequest($GLOBALS['TYPO3_REQUEST'])->isBackend()) {
            echo 'backend';
        }

        if($this->environmentService->isEnvironmentInCliMode()) {
            echo 'cli';
        }

        if($this->myCustomEnvironment->isEnvironmentInBackendMode()) {
            echo 'backend';
        }

        if($this->myCustomEnvironment->isEnvironmentInFrontendMode()) {
            echo 'backend';
        }
    }
}

?>

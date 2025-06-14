<?php

namespace Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\GeneralUtilityMakeInstanceToConstructorPropertyRector\Fixture\PHP81;

use Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\GeneralUtilityMakeInstanceToConstructorPropertyRector\Source\AnotherService;
use Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\GeneralUtilityMakeInstanceToConstructorPropertyRector\Source\MyService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ClassWithConstructorAndServiceInjection
{
    private AnotherService $anotherService;

    public function __construct(AnotherService $anotherService)
    {
        $this->anotherService = $anotherService;
    }

    public function myMethod(): void
    {
        $this->anotherService->doSomething();
        GeneralUtility::makeInstance(MyService::class)->doSomething();
    }
}
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\GeneralUtilityMakeInstanceToConstructorPropertyRector\Fixture\PHP81;

use Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\GeneralUtilityMakeInstanceToConstructorPropertyRector\Source\AnotherService;
use Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\GeneralUtilityMakeInstanceToConstructorPropertyRector\Source\MyService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ClassWithConstructorAndServiceInjection
{
    private AnotherService $anotherService;

    public function __construct(AnotherService $anotherService, private MyService $myService)
    {
        $this->anotherService = $anotherService;
    }

    public function myMethod(): void
    {
        $this->anotherService->doSomething();
        $this->myService->doSomething();
    }
}
?>

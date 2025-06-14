<?php

namespace Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\GeneralUtilityMakeInstanceToConstructorPropertyRector\Fixture\PHP74;

use Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\GeneralUtilityMakeInstanceToConstructorPropertyRector\Source\MyService;
use Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\GeneralUtilityMakeInstanceToConstructorPropertyRector\Source\ServiceWithEmptyConstructor;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ClassWithEmptyParentConstructor extends ServiceWithEmptyConstructor
{
    public function myMethod(): void
    {
        GeneralUtility::makeInstance(MyService::class)->doSomething();
    }
}
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\GeneralUtilityMakeInstanceToConstructorPropertyRector\Fixture\PHP74;

use Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\GeneralUtilityMakeInstanceToConstructorPropertyRector\Source\MyService;
use Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\GeneralUtilityMakeInstanceToConstructorPropertyRector\Source\ServiceWithEmptyConstructor;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ClassWithEmptyParentConstructor extends ServiceWithEmptyConstructor
{
    private MyService $myService;
    public function __construct(MyService $myService)
    {
        parent::__construct();
        $this->myService = $myService;
    }
    public function myMethod(): void
    {
        $this->myService->doSomething();
    }
}
?>

<?php

namespace Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\GeneralUtilityMakeInstanceToConstructorPropertyRector\Fixture\PHP80;

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

namespace Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\GeneralUtilityMakeInstanceToConstructorPropertyRector\Fixture\PHP80;

use Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\GeneralUtilityMakeInstanceToConstructorPropertyRector\Source\MyService;
use Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\GeneralUtilityMakeInstanceToConstructorPropertyRector\Source\ServiceWithEmptyConstructor;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ClassWithEmptyParentConstructor extends ServiceWithEmptyConstructor
{
    public function __construct(private MyService $myService)
    {
        parent::__construct();
    }
    public function myMethod(): void
    {
        $this->myService->doSomething();
    }
}
?>

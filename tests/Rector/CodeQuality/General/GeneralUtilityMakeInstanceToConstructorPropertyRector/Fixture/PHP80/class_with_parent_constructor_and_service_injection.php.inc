<?php

namespace Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\GeneralUtilityMakeInstanceToConstructorPropertyRector\Fixture\PHP80;

use Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\GeneralUtilityMakeInstanceToConstructorPropertyRector\Source\MyService;
use Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\GeneralUtilityMakeInstanceToConstructorPropertyRector\Source\ServiceWithConstructor;
use Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\GeneralUtilityMakeInstanceToConstructorPropertyRector\Source\YetAnotherService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ClassWithParentConstructorAndServiceInjection extends ServiceWithConstructor
{
    public function myMethod(): void
    {
        GeneralUtility::makeInstance(MyService::class)->doSomething();
        GeneralUtility::makeInstance(YetAnotherService::class)->doSomethingElse();
    }
}
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\GeneralUtilityMakeInstanceToConstructorPropertyRector\Fixture\PHP80;

use Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\GeneralUtilityMakeInstanceToConstructorPropertyRector\Source\MyService;
use Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\GeneralUtilityMakeInstanceToConstructorPropertyRector\Source\ServiceWithConstructor;
use Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\GeneralUtilityMakeInstanceToConstructorPropertyRector\Source\YetAnotherService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ClassWithParentConstructorAndServiceInjection extends ServiceWithConstructor
{
    public function __construct(\Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\GeneralUtilityMakeInstanceToConstructorPropertyRector\Source\AnInjectedService $injectedService, private MyService $myService)
    {
        parent::__construct($injectedService);
    }
    public function myMethod(): void
    {
        $this->myService->doSomething();
        GeneralUtility::makeInstance(YetAnotherService::class)->doSomethingElse();
    }
}
?>

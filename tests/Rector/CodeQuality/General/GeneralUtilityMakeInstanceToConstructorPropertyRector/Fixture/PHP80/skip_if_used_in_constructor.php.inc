<?php

namespace Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\GeneralUtilityMakeInstanceToConstructorPropertyRector\Fixture\PHP80;

use Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\GeneralUtilityMakeInstanceToConstructorPropertyRector\Source\MyService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class SkipIfUsedInConstuctor
{
    private MyService $myService;

    public function __construct()
    {
        $this->myService = GeneralUtility::makeInstance(MyService::class);
    }

    private function doSomething(): void
    {
        $this->myService->doSomething();
    }
}

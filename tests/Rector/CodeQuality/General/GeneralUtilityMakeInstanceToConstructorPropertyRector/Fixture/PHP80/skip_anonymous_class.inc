<?php

namespace Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\GeneralUtilityMakeInstanceToConstructorPropertyRector\Fixture\PHP80;

use Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\GeneralUtilityMakeInstanceToConstructorPropertyRector\Source\MyService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$service = new class {
    public function myMethod(): void
    {
        GeneralUtility::makeInstance(MyService::class)->doSomething();
    }
};

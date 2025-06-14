<?php

namespace Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\GeneralUtilityMakeInstanceToConstructorPropertyRector\Fixture\PHP74;

use Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\GeneralUtilityMakeInstanceToConstructorPropertyRector\Source\MyService;
use Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\GeneralUtilityMakeInstanceToConstructorPropertyRector\Source\YetAnotherService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class SkipClassWithConstructorHavingScalarType
{
    public function __construct(string $argument)
    {
    }

    public function myMethod(): void
    {
        GeneralUtility::makeInstance(MyService::class)->doSomething();
        GeneralUtility::makeInstance(YetAnotherService::class)->doSomethingElse();
    }
}

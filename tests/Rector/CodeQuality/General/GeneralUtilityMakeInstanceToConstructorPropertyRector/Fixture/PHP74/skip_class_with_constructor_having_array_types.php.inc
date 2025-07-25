<?php

namespace Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\GeneralUtilityMakeInstanceToConstructorPropertyRector\Fixture\PHP74;

use Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\GeneralUtilityMakeInstanceToConstructorPropertyRector\Source\MyService;
use Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\GeneralUtilityMakeInstanceToConstructorPropertyRector\Source\YetAnotherService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class SkipClassWithConstructorHavingArrayType
{
    public function __construct(array $arguments)
    {
    }

    public function myMethod(): void
    {
        GeneralUtility::makeInstance(MyService::class)->doSomething();
        GeneralUtility::makeInstance(YetAnotherService::class)->doSomethingElse();
    }
}

<?php

namespace Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\GeneralUtilityMakeInstanceToConstructorPropertyRector\Fixture\PHP81;

use Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\GeneralUtilityMakeInstanceToConstructorPropertyRector\Source\MyService;
use Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\GeneralUtilityMakeInstanceToConstructorPropertyRector\Source\ServiceWithConstructorAndArrayType;
use Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\GeneralUtilityMakeInstanceToConstructorPropertyRector\Source\YetAnotherService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class SkipClassWithParentConstructorHavingArrayType extends ServiceWithConstructorAndArrayType
{
    public function myMethod(): void
    {
        GeneralUtility::makeInstance(MyService::class)->doSomething();
        GeneralUtility::makeInstance(YetAnotherService::class)->doSomethingElse();
    }
}

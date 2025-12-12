<?php

namespace Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\InjectMethodToConstructorInjectionRector\Fixture;

use Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\InjectMethodToConstructorInjectionRector\Source\MyService;

abstract class SkipIfAbstractClass
{
    private MyService $myService;

    public function injectMyService(MyService $myService): void
    {
        $this->myService = $myService;
    }
}

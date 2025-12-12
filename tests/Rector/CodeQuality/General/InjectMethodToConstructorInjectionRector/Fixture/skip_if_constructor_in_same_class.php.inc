<?php

namespace Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\InjectMethodToConstructorInjectionRector\Fixture;

use Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\InjectMethodToConstructorInjectionRector\Source\MyService;

class SkipIfConstructorInSameClass
{
    private MyService $myService;
    private bool $isInitialized;

    public function __construct()
    {
        $this->isInitialized = true;
    }

    public function injectMyService(MyService $myService): void
    {
        $this->myService = $myService;
    }

    public function getServiceStatus(): bool
    {
        return isset($this->myService) && $this->isInitialized;
    }
}

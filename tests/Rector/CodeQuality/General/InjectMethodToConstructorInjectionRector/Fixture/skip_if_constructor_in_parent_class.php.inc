<?php

namespace Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\InjectMethodToConstructorInjectionRector\Fixture;

use Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\InjectMethodToConstructorInjectionRector\Source\AnotherService;
use Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\InjectMethodToConstructorInjectionRector\Source\YetAnotherService;

class BaseServiceWithConstructor
{
    protected YetAnotherService $baseDep;

    public function __construct(YetAnotherService $baseDep)
    {
        $this->baseDep = $baseDep;
    }
}

class SkipIfConstructorInParentClass extends BaseServiceWithConstructor
{
    private AnotherService $anotherService;

    // This inject method should not be processed because BaseServiceWithConstructor has a constructor
    public function injectAnotherService(AnotherService $anotherService): void
    {
        $this->anotherService = $anotherService;
    }

    public function getAnotherService(): AnotherService
    {
        return $this->anotherService;
    }
}
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\InjectMethodToConstructorInjectionRector\Fixture;

use Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\InjectMethodToConstructorInjectionRector\Source\AnotherService;
use Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\InjectMethodToConstructorInjectionRector\Source\YetAnotherService;

class BaseServiceWithConstructor
{
    protected YetAnotherService $baseDep;

    public function __construct(YetAnotherService $baseDep)
    {
        $this->baseDep = $baseDep;
    }
}

class SkipIfConstructorInParentClass extends BaseServiceWithConstructor
{
    private AnotherService $anotherService;

    // This inject method should not be processed because BaseServiceWithConstructor has a constructor
    public function injectAnotherService(AnotherService $anotherService): void
    {
        $this->anotherService = $anotherService;
    }

    public function getAnotherService(): AnotherService
    {
        return $this->anotherService;
    }
}
?>

<?php

namespace Ssch\TYPO3Rector\Tests\Rector\General\Transform\MethodCallToStaticCallRector\Fixture;

use Ssch\TYPO3Rector\Tests\Rector\General\Transform\MethodCallToStaticCallRector\Source\AnotherDependency;

final class SomeClass
{
    private AnotherDependency $anotherDependency;

    public function __construct(AnotherDependency $anotherDependency)
    {
        $this->anotherDependency = $anotherDependency;
    }

    public function loadConfiguration()
    {
        return $this->anotherDependency->process('value');
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\General\Transform\MethodCallToStaticCallRector\Fixture;

use Ssch\TYPO3Rector\Tests\Rector\General\Transform\MethodCallToStaticCallRector\Source\StaticCaller;
use Ssch\TYPO3Rector\Tests\Rector\General\Transform\MethodCallToStaticCallRector\Source\AnotherDependency;

final class SomeClass
{
    private AnotherDependency $anotherDependency;

    public function __construct(AnotherDependency $anotherDependency)
    {
        $this->anotherDependency = $anotherDependency;
    }

    public function loadConfiguration()
    {
        return StaticCaller::anotherMethod('value');
    }
}

?>

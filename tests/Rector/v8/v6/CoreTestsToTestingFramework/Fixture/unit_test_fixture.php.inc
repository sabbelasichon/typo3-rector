<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v8\v6\CoreTestsToTestingFramework\Fixture;

use TYPO3\CMS\Core\Tests\UnitTestCase;

class MyUnitTest extends UnitTestCase
{
    public function testFooBar()
    {

    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v8\v6\CoreTestsToTestingFramework\Fixture;

use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
use TYPO3\CMS\Core\Tests\UnitTestCase;

class MyUnitTest extends UnitTestCase
{
    public function testFooBar()
    {

    }
}

?>

<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v8\v6\CoreTestsToTestingFrameworkRector\Fixture;

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

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v8\v6\CoreTestsToTestingFrameworkRector\Fixture;

use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class MyUnitTest extends UnitTestCase
{
    public function testFooBar()
    {

    }
}

?>

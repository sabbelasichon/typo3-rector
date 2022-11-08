<?php

namespace Ssch\TYPO3Rector\Tests\Rector\Nimut\TestingFramework\Fixture;

use Nimut\TestingFramework\Exception\Exception;
use Nimut\TestingFramework\MockObject\AccessibleMockObjectInterface;
use Nimut\TestingFramework\TestCase\UnitTestCase;

class MyUnitTest extends UnitTestCase
{
    /**
     * @var AccessibleMockObjectInterface
     */
    protected $fooBarBaz;

    public function testFooBar()
    {
        try {

        } catch (Exception $e) {

        }
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\Nimut\TestingFramework\Fixture;

use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
use TYPO3\TestingFramework\Core\AccessibleObjectInterface;
use TYPO3\TestingFramework\Core\Exception;

class MyUnitTest extends UnitTestCase
{
    /**
     * @var AccessibleObjectInterface
     */
    protected $fooBarBaz;

    public function testFooBar()
    {
        try {

        } catch (Exception $e) {

        }
    }
}

?>

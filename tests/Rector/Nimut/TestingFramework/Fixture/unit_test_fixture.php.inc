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

use Nimut\TestingFramework\Exception\Exception;
use Nimut\TestingFramework\MockObject\AccessibleMockObjectInterface;
use Nimut\TestingFramework\TestCase\UnitTestCase;

class MyUnitTest extends \TYPO3\TestingFramework\Core\Unit\UnitTestCase
{
    /**
     * @var \TYPO3\TestingFramework\Core\AccessibleObjectInterface
     */
    protected $fooBarBaz;

    public function testFooBar()
    {
        try {

        } catch (\TYPO3\TestingFramework\Core\Exception $e) {

        }
    }
}

?>

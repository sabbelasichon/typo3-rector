<?php

namespace Ssch\TYPO3Rector\Tests\Rector\Core\Package;

use Iterator;
use Ssch\TYPO3Rector\Tests\AbstractRectorWithConfigTestCase;

class UsePackageManagerActivePackagesRectorTest extends AbstractRectorWithConfigTestCase
{
    /**
     * @dataProvider provideDataForTest()
     */
    public function test(string $file): void
    {
        $this->doTestFile($file);
    }

    public function provideDataForTest(): Iterator
    {
        yield [__DIR__ . '/Fixture/package_manager_active_packages.php.inc'];
    }
}

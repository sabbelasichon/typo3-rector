<?php

namespace Ssch\TYPO3Rector\Tests\Core\Environment;

use Iterator;
use Ssch\TYPO3Rector\Rector\Core\Environment\ConstantToEnvironmentCallRector;
use Ssch\TYPO3Rector\Rector\Core\Environment\RenameMethodCallToEnvironmentMethodCallRector;
use Ssch\TYPO3Rector\Tests\AbstractRectorWithConfigTestCase;

class EnvironmentApiTest extends AbstractRectorWithConfigTestCase
{
    /**
     * @dataProvider provideDataForTest()
     *
     * @param string $file
     */
    public function test(string $file): void
    {
        $this->doTestFile($file);
    }

    public function provideDataForTest(): Iterator
    {
        yield [__DIR__ . '/Fixture/environment_constants.php.inc'];
    }

    protected function getRectorsWithConfiguration(): array
    {
        return [
            ConstantToEnvironmentCallRector::class => [],
            RenameMethodCallToEnvironmentMethodCallRector::class => [],
        ];
    }
}
